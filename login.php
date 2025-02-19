<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lib/JWT.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['username']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(["error" => "Username и пароль обязательны"]);
    exit;
}

$username = $data['username'];
$password = $data['password'];

// Поиск пользователя в базе
$stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(["error" => "Неверные учетные данные"]);
    exit;
}

// Генерация JWT
$secret_key = "your_secret_key"; // Храним в безопасном месте
$payload = [
    "user_id" => $user['id'],
    "exp" => time() + (60 * 60 * 24) // Токен на 24 часа
];

$jwt = JWT::encode($payload, $secret_key, 'HS256');

echo json_encode(["token" => $jwt]);
?>
