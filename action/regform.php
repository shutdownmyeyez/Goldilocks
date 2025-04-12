<?php
include "../components/core.php";

// Проверка имени кнопки
if(isset($_POST['submit-btn'])) {
    
    // Проверка соединения
    if($mysqli->connect_errno) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Экранирование данных
    $fullname = $mysqli->real_escape_string($_POST['fullname']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $phone = $mysqli->real_escape_string($_POST['phone']);
    $login = $mysqli->real_escape_string($_POST['login']);
    $password = $mysqli->real_escape_string($_POST['password']);

    // Выполнение запроса
    $sql = "INSERT INTO users 
            (fullname, email, phone, login, pass, role_id) 
            VALUES 
            ('$fullname', '$email', '$phone', '$login', '$password', 2)";
    
    if($mysqli->query($sql)) {
        header("Location: ../index.php");
        exit();
    } else {
        echo "Ошибка: " . $mysqli->error;
    }
}
?>