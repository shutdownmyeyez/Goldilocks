<?php
session_start();
require_once '../components/core.php';

// Проверка прав администратора
if (!isset($_SESSION['user']['role_id']) || $_SESSION['user']['role_id'] != 3) {
    header("Location: /access_denied.php");
    exit();
}

// Проверка CSRF
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Ошибка безопасности!");
}

// В начало файла после проверки CSRF добавить:
if($_POST['action'] === 'edit') {
    $requirements = array_map('trim', explode("\n", $_POST['requirements']));
    
    $stmt = $mysqli->prepare("
        UPDATE vacancies 
        SET title = ?,
            salary = ?,
            description = ?,
            requirements = ?,
            updated_at = NOW()
        WHERE id = ?
    ");
    
    $stmt->bind_param("ssssi", 
        $_POST['title'],
        $_POST['salary'],
        $_POST['description'],
        json_encode($requirements, JSON_UNESCAPED_UNICODE),
        $_POST['id']
    );
}

$mysqli->begin_transaction();

try {
    if($_POST['action'] === 'add') {
        $requirements = array_map('trim', explode("\n", $_POST['requirements']));
        $stmt = $mysqli->prepare("
            INSERT INTO vacancies (title, salary, description, requirements)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("ssss", 
            $_POST['title'],
            $_POST['salary'],
            $_POST['description'],
            json_encode($requirements, JSON_UNESCAPED_UNICODE)
        );
    }
    elseif($_POST['action'] === 'delete') {
        $stmt = $mysqli->prepare("DELETE FROM vacancies WHERE id = ?");
        $stmt->bind_param("i", $_POST['id']);
    }

    $stmt->execute();
    $mysqli->commit();
    header("Location: /account.php?success=vacancy");
} catch(Exception $e) {
    $mysqli->rollback();
    header("Location: /account.php?error=vacancy");
}