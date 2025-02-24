<?php
require "../config.php";
require_once "../auth.php"; // Проверяет токен и дает $user_id

header("Content-Type: application/json");

// Получение всех задач
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (!isset($_GET['direction'])) {
        echo json_encode(["error" => "The direction is not specified"]);
        exit;
    }
    
    $direction = trim($_GET['direction']);
    $sql = "SELECT * FROM task_tags WHERE direction = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$direction]);
    $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($tags, JSON_UNESCAPED_UNICODE);
    
}
?>
