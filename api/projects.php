<?php
require "../config.php";
$user_id = require_once "../auth.php"; // Проверяет токен и дает $user_id


if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}
$method = $_SERVER["REQUEST_METHOD"];


// Получение проектов
if ($method === "GET") {
    try {
        $sql =  "SELECT 
            CAST(p.id AS UNSIGNED) AS id,
            p.name,
            p.description,
            p.type,
            p.status,
            p.icon,
            p.icon_bg,
            JSON_OBJECT(
                'id', CAST(u.id AS UNSIGNED), 
                'email', u.email, 
                'full_name', u.full_name
            ) AS created_by,
            IFNULL(
                JSON_ARRAYAGG(
                    JSON_OBJECT(
                        'id', CAST(members.id AS UNSIGNED), 
                        'email', members.email, 
                        'full_name', members.full_name
                    )
                ), JSON_ARRAY()
            ) AS members
            FROM projects p
            LEFT JOIN users u ON p.created_by = u.id
            LEFT JOIN project_members pm ON p.id = pm.project_id
            LEFT JOIN users members ON pm.user_id = members.id
            WHERE p.id IN (
                SELECT project_id FROM project_members WHERE user_id = ?
            )
            GROUP BY p.id, p.name, p.description, p.type, p.status, p.icon, p.icon_bg, u.id, u.email, u.full_name;";
                    
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id]);

        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($projects as &$project) {
            // Преобразуем id в число
            $project["id"] = (int) $project["id"];
            $project["created_by"] = json_decode($project["created_by"], true); // Объект
            $project["members"] = json_decode($project["members"], true) ?? []; // Массив
        }

        if (empty($projects)) {
            echo json_encode(["no-data" => "Проекты не найдены"], JSON_UNESCAPED_UNICODE);
            exit;
        }

        echo json_encode($projects, JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Ошибка запроса: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
}


// Создание проекта
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = $_POST;

    // Вставляем основную задачу
    $sql = "INSERT INTO projects(name, description, type, status, icon, icon_bg, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([
            $data["project_name"],
            $data["project_description"],
            $data["project_type"],
            $data["project_status"],
            $data["project_icon"],
            $data["project_icon_bg"],
            $user_id
        ]);

        // Получаем ID созданной задачи
        $projectId = $pdo->lastInsertId();

        // Обрабатываем assigned_to
        $projectMembers = isset($data['project_members']) ? explode(',', $data['project_members']) : [];

        // Если в массиве только одно значение, можно обрабатывать его отдельно
        if (count($projectMembers) === 1 && $projectMembers[0] !== '') {
            // Если это одно число, можем добавить его в базу
            $memberId = trim($projectMembers[0]);
            $pdo->prepare("INSERT INTO project_members(project_id, user_id) VALUES (?, ?)")
                ->execute([$projectId, $memberId]);
        } else {
            // Вставляем исполнителей
            foreach ($projectMembers as $memberId) {
                $memberId = trim($memberId);
                if ($memberId !== '') { // Проверяем на пустые значения
                    $pdo->prepare("INSERT INTO project_members(project_id, user_id) VALUES (?, ?)")
                        ->execute([$projectId, $memberId]);
                }
            }
        }

        echo json_encode(["success" => "The project has been created"]);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Error: " . $e->getMessage()]);
    }
}
?>
