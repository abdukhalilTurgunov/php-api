<?php
require "../config.php";
$user_id = require "../auth.php";    
// header("Content-Type: application/json");

// Регистрация пользователя
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data["email"], $data["password"], $data["full_name"], $data['photo'], $data['role'])) {
        echo json_encode(["error" => "Заполните все поля"]);
        exit;
    }

    $password_hash = password_hash($data["password"], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (email, password, full_name, photo, can_assign_tasks, role) VALUES (?, ?, ?, ?, 1, ?)";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([$data["email"], $password_hash, $data["full_name"], $data['photo'], $data['role']]);
        echo json_encode(["success" => "Пользователь зарегистрирован"]);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Ошибка: " . $e->getMessage()]);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    try{
        $sql = "SELECT * from users WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($user, JSON_UNESCAPED_UNICODE);
    }catch (PDOException $e) {
        echo json_encode(["error" => "Request error:" . $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
}
?>
