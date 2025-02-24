<?php
require "../config.php";
require "../auth.php";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = $_POST;

    if (!isset($data["task_id"], $data["status"])) {
        echo json_encode(["error" => "Не переданы все данные"]);
        exit;
    }

    $sql = "UPDATE tasks SET status = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$data["status"], $data["task_id"]]);
        echo json_encode(["success" => "Status updated"]);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Error: " . $e->getMessage()]);
    }
}
?>