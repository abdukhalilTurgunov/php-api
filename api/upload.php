<?php
require '../config.php';
require '../auth.php';
    
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (!isset($_FILES["img"])) {
            echo json_encode(["error" => "Файл не загружен"]);
            exit;
        }
    
        $file = $_FILES["img"];
        $allowed_types = ["image/jpeg", "image/png"];
        
        if (!in_array($file["type"], $allowed_types)) {
            echo json_encode(["error" => "Invalid file format. JPG, PNG are allowed"]);
            exit;
        }
    
        $type = $_POST["type"] ?? "task_image"; // По умолчанию "task_image"
        
        // Определяем путь загрузки
        $uploadDir = __DIR__ . "/../media/";
        if ($type === "avatar") {
            $uploadDir .= "users/"; // Папка для аватаров
        } else {
            $uploadDir .= "tasks/"; // Папка для изображений задач
        }
    
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
    
        // Генерация уникального имени файла
        $extension = pathinfo($file["name"], PATHINFO_EXTENSION);
        $fileName = uniqid($type . "_", true) . "." . $extension;
        $filePath = $uploadDir . $fileName;
        
        if (move_uploaded_file($file["tmp_name"], $filePath)) {
            $imageUrl = "http://code-storm/media/" . ($type === "avatar" ? "users/" : "tasks/") . $fileName;
            echo json_encode(["url" => $imageUrl]);
        } else {
            echo json_encode(["error" => "Error saving the file"]);
        }
    } else {
        echo json_encode(["error" => "Invalid request method"]);
    }
?>