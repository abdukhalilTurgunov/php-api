<?php
require "../config.php";
$user_id = require "../auth.php";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = $_POST;

    $sql = "UPDATE users SET full_name = ?, email = ?, github_link = ?, linkedin_link = ?";
    $params = [
        $data["full_name"],
        $data["email"],
        $data["github"],
        $data["linkedin"]
    ];

    // Проверяем, передано ли изображение и не является ли оно null
    if (!empty($data["img"]) && $data["img"] !== "null") {
        $sql .= ", photo = ?";
        $params[] = $data["img"];
    }

    $sql .= " WHERE id = ?";
    $params[] = $user_id;

    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute($params);
        echo json_encode(["success" => "The data has been updated"]);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Error: " . $e->getMessage()]);
    }
}
?>