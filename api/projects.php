<?php
require "../config.php";
require_once "../auth.php"; // Проверяет токен и дает $user_id

header("Content-Type: application/json");

$method = $_SERVER["REQUEST_METHOD"];

// Получение проектов
if ($method === "GET") {
    $sql = "SELECT * FROM projects WHERE JSON_CONTAINS(members, CAST(? AS JSON))";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([json_encode($user_id)]);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
}

// Создание проекта
if ($method === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["name"], $data["description"], $data["type"], $data["status"], $data["members"]) || !is_array($data["members"])) {
        echo json_encode(["error" => "Заполните все поля и передайте массив members"]);
        exit;
    }

    $sql = "INSERT INTO projects (name, description, type, status, icon, icon_bg, members) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([
            $data["name"],
            $data["description"],
            $data["type"],
            $data["status"],
            "webSite.svg",
            "FFEAF8",
            json_encode(array_map('intval', $data["members"]))
        ]);
        echo json_encode(["success" => "Проект создан"]);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Ошибка: " . $e->getMessage()]);
    }
}
?>
