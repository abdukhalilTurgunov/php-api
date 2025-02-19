<?php
require "config.php";
header("Content-Type: application/json");

// Регистрация пользователя
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data["username"], $data["password"], $data["full_name"])) {
        echo json_encode(["error" => "Заполните все поля"]);
        exit;
    }

    $password_hash = password_hash($data["password"], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (username, password, full_name, photo, can_assign_tasks) VALUES (?, ?, ?, '', 0)";
    $stmt = $pdo->prepare($sql);
    echo $_POST;
    try {
        $stmt->execute([$data["username"], $password_hash, $data["full_name"]]);
        echo json_encode(["success" => "Пользователь зарегистрирован"]);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Ошибка: " . $e->getMessage()]);
    }
}

// Авторизация
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["login"])) {
    $data = json_decode(file_get_contents("php://input"), true);
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$data["username"]]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($data["password"], $user["password"])) {
        echo json_encode(["success" => "Вход выполнен", "user" => $user]);
    } else {
        echo json_encode(["error" => "Неверный логин или пароль"]);
    }
}
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    echo json_encode(["message" => "API работает"]);
}
?>
