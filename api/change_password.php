<?php
require "../config.php";
$user_id = require "../auth.php";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = $_POST;
    $newPassword = $_POST['new_password'];
    
    // Хешируем новый пароль
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Обновляем пароль в базе данных
    $sql = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$hashedPassword, $user_id]);
    if($result){
        echo('Password has been changed succsesfully');
    }
}
?>