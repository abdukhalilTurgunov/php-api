<?php
require "../config.php";
require_once "../auth.php"; // Проверяет токен и дает $user_id

header("Content-Type: application/json");

// Получение всех задач
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $sql = "SELECT 
        t.id, 
        JSON_OBJECT(
            'id', p.id,
            'name', p.name
        ) AS project,
        t.title,
        t.status,
        t.deadline,
        JSON_OBJECT(
            'id', creator.id,
            'full_name', creator.full_name
        ) AS created_by,
        COALESCE(
            (SELECT JSON_ARRAYAGG(
                JSON_OBJECT(
                    'id', u.id, 
                    'full_name', u.full_name
                )
            ) 
             FROM task_assigned_to 
             JOIN users u ON u.id = task_assigned_to.user_id
             WHERE task_assigned_to.task_id = t.id), 
            '[]'
        ) AS assigned_to,
        t.created_date
    FROM tasks t
    LEFT JOIN users creator ON t.created_by = creator.id
    LEFT JOIN projects p ON t.project_id = p.id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Преобразуем JSON-строки в массивы
    foreach ($tasks as &$task) {
        $task['project'] = json_decode($task['project'], true);
        $task['created_by'] = json_decode($task['created_by'], true);
        $task['assigned_to'] = json_decode($task['assigned_to'], true);
    }

    echo json_encode($tasks, JSON_UNESCAPED_UNICODE);
}
?>
