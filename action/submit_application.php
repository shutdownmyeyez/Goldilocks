<?php
require_once "../components/core.php";
checkAuth();
if(!strtotime($_POST['date'])) {
    $errors[] = "Некорректная дата";
}
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    
    // Валидация данных
    $required = ['fullname', 'date', 'service_id'];
    foreach($required as $field) {
        if(empty($_POST[$field])) {
            $errors[] = "Поле $field обязательно для заполнения";
        }
    }
    
    if(count($errors) === 0) {
        // Экранирование данных
        $fullname = $mysqli->real_escape_string($_POST['fullname']);
        $date = $mysqli->real_escape_string($_POST['date']);
        $service_id = (int)$_POST['service_id'];
        $user_id = isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : 'NULL';
        
        // Проверка существования услуги
        $check_service = $mysqli->query("SELECT id FROM services WHERE id = $service_id");
        if($check_service->num_rows === 0) {
            $errors[] = "Выбранная услуга не существует";
        }
        
        if(count($errors) === 0) {
            // Вставка данных
            $query = "INSERT INTO states 
                     (user_id, service_id, fullname, date) 
                     VALUES ($user_id, $service_id, '$fullname', '$date')";
            
            if($mysqli->query($query)) {
                header('Location: ../index.php');
                exit();
            } else {
                $errors[] = "Ошибка базы данных: " . $mysqli->error;
            }
        }
    }
    
    if(count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        header('Location: popup.php');
        exit();
    }
}