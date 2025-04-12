<?php
session_start();
require_once "../components/core.php";

if ($_POST) {
    $login = $mysqli->real_escape_string($_POST['login']);
    $password = $mysqli->real_escape_string($_POST['password']);

    $result = $mysqli->query("
        SELECT * 
        FROM users 
        WHERE login = '$login'
    ");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Если пароли хранятся в открытом виде:
        if ($password === $user['pass']) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'role_id' => $user['role_id']
            ];
            header("Location: ../index.php");
            exit();
        }
        // Если пароли хэшированы:
        // if (password_verify($password, $user['pass'])) { ... }
    }

    // Если авторизация не удалась
    header("Location: ../signUp.php?error=1");
    exit();
}

?>
