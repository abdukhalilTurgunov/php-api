<?php
require_once __DIR__ . '/config.php';
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}


$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(["error" => "Email and password are required"]);
    exit;
}

$email = $data['email'];
$password = $data['password'];

// Поиск пользователя в базе
$stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid credentials"]);
    exit;
}

// Генерация JWT
$secret_key = "admin123";
$payload = [
    "user_id" => $user['id'],
    "exp" => time() + (60 * 60 * 24 * 365)
];

$jwt = JWT::encode($payload, $secret_key, 'HS256');

echo json_encode(["token" => $jwt]);
?>
