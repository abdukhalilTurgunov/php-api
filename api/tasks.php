<?php
require "../config.php";
$user_id = require_once "../auth.php"; // Проверяет токен и дает $user_id

header("Content-Type: application/json");

// Получение всех задач
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (!isset($_GET['project_id'])) {
        echo json_encode(["error" => "Не указан project_id"]);
        exit;
    }
    
    $project_id = intval($_GET['project_id']);
    
    $sql = "SELECT 
                t.id, 
                JSON_OBJECT(
                    'id', p.id,
                    'name', p.name,
                    'description', p.description
                ) AS project,
                t.title,
                t.description,
                t.img,
                t.status,
                t.deadline,
                t.tag,
                t.tag_bg,
                JSON_OBJECT(
                    'id', creator.id,
                    'full_name', creator.full_name,
                    'photo', creator.photo
                ) AS created_by,
                COALESCE(
                    (SELECT JSON_ARRAYAGG(
                        JSON_OBJECT(
                            'id', u.id, 
                            'full_name', u.full_name, 
                            'photo', u.photo
                        )
                    ) 
                     FROM task_assigned_to 
                     JOIN users u ON u.id = task_assigned_to.user_id
                     WHERE task_assigned_to.task_id = t.id), 
                    '[]'
                ) AS assigned_to,
                t.created_date,
                t.completed_by,
                t.completed_day
            FROM tasks t
            LEFT JOIN users creator ON t.created_by = creator.id
            LEFT JOIN projects p ON t.project_id = p.id
            WHERE t.project_id = ? AND t.status != 'closed'";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$project_id]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Преобразуем JSON-строки в массивы
    foreach ($tasks as &$task) {
        $task['project'] = json_decode($task['project'], true);
        $task['created_by'] = json_decode($task['created_by'], true);
        $task['assigned_to'] = json_decode($task['assigned_to'], true);
    }
    
    echo json_encode($tasks, JSON_UNESCAPED_UNICODE);
}

// Создание задачи
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = $_POST;

    // Вставляем основную задачу
    $sql = "INSERT INTO tasks (project_id, created_by, deadline, status, title, description, img, tag, tag_bg)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([
            $data["project"],
            $data["created_by"],
            $data["deadline"],
            $data["status"],
            $data["title"],
            $data["description"],
            $data["img"], // Просто сохраняем ссылку
            $data["tag"],
            $data["tag_bg"]
        ]);

        // Получаем ID созданной задачи
        $taskId = $pdo->lastInsertId();

        // Обрабатываем assigned_to
        $assignedUsers = isset($data['assigned_to']) ? explode(',', $data['assigned_to']) : [];

        // Если в массиве только одно значение, можно обрабатывать его отдельно
        if (count($assignedUsers) === 1 && $assignedUsers[0] !== '') {
            // Если это одно число, можем добавить его в базу
            $userId = trim($assignedUsers[0]);
            $pdo->prepare("INSERT INTO task_assigned_to (task_id, user_id) VALUES (?, ?)")
                ->execute([$taskId, $userId]);
        } else {
            // Вставляем исполнителей
            foreach ($assignedUsers as $userId) {
                $userId = trim($userId);
                if ($userId !== '') { // Проверяем на пустые значения
                    $pdo->prepare("INSERT INTO task_assigned_to (task_id, user_id) VALUES (?, ?)")
                        ->execute([$taskId, $userId]);
                }
            }
        }

        echo json_encode(["success" => "The task has been created"]);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Error: " . $e->getMessage()]);
    }
}

?>
