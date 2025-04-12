<?php
session_start();
require_once '../components/core.php';

// Проверка авторизации и CSRF
if (!isset($_SESSION['user']['id']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header("Location: /access_denied.php");
    exit();
}

// Обработка данных
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = (int)$_POST['application_id'];
    $user_id = $_SESSION['user']['id'];
    
    // Проверка прав на редактирование
    $check = $mysqli->prepare("SELECT id FROM states WHERE id = ? AND user_id = ?");
    $check->bind_param("ii", $application_id, $user_id);
    $check->execute();
    
    if(!$check->get_result()->num_rows) {
        die("Нет прав на редактирование этой заявки");
    }

    // Обновление данных
    $stmt = $mysqli->prepare("
        UPDATE states 
        SET service_id = ?, 
            date = ?, 
            fullname = ?
        WHERE id = ?
    ");
    
    $stmt->bind_param("issi", 
        $_POST['service_id'],
        $_POST['date'],
        $_POST['fullname'],
        $application_id
    );
    
    if($stmt->execute()) {
        header("Location: /states.php");
    } else {
        header("Location: /applications.php?error=1");
    }
    exit();
}