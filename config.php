<?php
$host = "localhost"; // Хост
$db_name = "code-storm"; // Имя базы
$username = "root"; // Логин (по умолчанию root в OpenServer)
$password = ""; // Пароль (обычно пустой в OpenServer)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
?>
