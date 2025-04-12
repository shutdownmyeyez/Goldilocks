<?php
session_start();
$mysqli = new mysqli('MySQL-5.7', 'root', '', 'goldilocks');
if ($mysqli->connect_error) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');

// Функция проверки авторизации
function checkAuth() {
    if(!isset($_SESSION['user'])) {
        header("Location: signUp.php");
        exit();
    }
}

// Функция проверки роли
function checkRole($required_role) {
    if($_SESSION['user']['role_id'] != $required_role) {
        header("Location: index.php");
        exit();
    }
}

// Генерация CSRF-токена
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>