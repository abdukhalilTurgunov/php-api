<?php
require_once __DIR__ . '/lib/JWT.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$secret_key = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImV4cCI6MTc0MDA4NTgyOH0.I84oQaUQv54pxIWmOb48e4CUnJ4iKkd5W1S43YogtuI"; // Должен совпадать с ключом генерации

// Получаем токен из заголовка
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(["error" => "Токен не передан"]);
    exit;
}

$token = str_replace("Bearer ", "", $headers['Authorization']);

try {
    $decoded = Firebase/JWT/JWT::decode($token, new Key($secret_key, 'HS256'));
    $user_id = $decoded->user_id; // Доступ к ID пользователя
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["error" => "Недействительный токен"]);
    exit;
}
?>
