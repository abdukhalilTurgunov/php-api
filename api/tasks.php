<?php
require "config.php";
header("Content-Type: application/json");

// Получение всех задач
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $sql = "SELECT * FROM tasks";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

// Создание задачи
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data["title"], $data["status"])) {
        echo json_encode(["error" => "Заполните все поля"]);
        exit;
    }

    $sql = "INSERT INTO tasks (title, description, status, created_by) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$data["title"], $data["description"], $data["status"], $data["created_by"]]);
        echo json_encode(["success" => "Задача создана"]);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Ошибка: " . $e->getMessage()]);
    }
}
?>
