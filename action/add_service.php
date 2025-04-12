<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../components/core.php";

// Проверка аутентификации и прав администратора
// if(!isset($_SESSION['user'])) {
//     die("Доступ запрещен: требуется авторизация");
// }

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. Проверка загрузки файла
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Ошибка загрузки файла: " . $_FILES['image']['error']);
        }

        // 2. Подготовка SQL-запроса
        $sql = "INSERT INTO services 
                (name, description, price, image) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $mysqli->prepare($sql);
        
        // 3. Обработка ошибки подготовки запроса
        if(!$stmt) {
            throw new Exception("Ошибка подготовки запроса: " . $mysqli->error);
        }

        // 4. Обработка данных
        $price = (int)$_POST['price'];
        $filename = uniqid() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $target_path = "../img/services/" . $filename;

        // 5. Привязка параметров
        $stmt->bind_param("ssis", 
            $_POST['name'],
            $_POST['description'],
            $price,
            $filename
        );

        // 6. Выполнение запроса
        if(!$stmt->execute()) {
            throw new Exception("Ошибка выполнения запроса: " . $stmt->error);
        }

        // 7. Сохранение файла
        if(!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            throw new Exception("Не удалось сохранить файл");
        }

        header("Location: ../account.php");
        exit();

    } catch (Exception $e) {
        die("Ошибка: " . $e->getMessage());
    }
} else {
    header("Location: ../index.php");
}
?>