<?php
require "../config.php";
require "../auth.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    try{
        $sql = "SELECT id, full_name from users";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users, JSON_UNESCAPED_UNICODE);
    }catch (PDOException $e) {
        echo json_encode(["error" => "Request error: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
}
?>
