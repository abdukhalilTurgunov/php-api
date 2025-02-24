<?php
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


// Получаем токен из заголовка
$headers = getallheaders();

if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(["error" => "The token has not been transferred"]);
    exit;
}

$secret_key = "admin123";
$token = str_replace("Bearer ", "", $headers['Authorization']);


try {
    $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
    $user_id = $decoded->user_id; // Доступ к ID пользователя
    return $user_id;
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["error" => "Недействительный токен"]);
    exit;
}
?>
