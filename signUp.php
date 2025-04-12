<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/popUp.js"></script>
    <title>Авторизация - Goldilocks</title>
</head>
<body>
<?php
   include "components/header.php";
   include "components/popUp.php";
?>

    <!-- Основной контент -->
    <main class="registration">
        <div class="container">
            <div class="registration-form">
                <h2 class="form-title">Вход в систему</h2>
                <form id="authForm" method="post" action="action/auth.php">
                    <div class="form-group">
                        <label for="login">Логин:</label>
                        <input type="text" id="login" name="login" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Пароль:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <?php if(isset($_GET['error'])): ?>
                    <div class="error-message">
                        Неверный логин или пароль
                    </div>
                    <?php endif; ?>
                    <button type="submit" name="submit-btn" class="submit-btn">Войти</button>
                </form>
                <a href="reg.php">Ещё нет аккаунта?</a>
            </div>
        </div>
    </main>

<?php
 include "components/footer.php";
?> 
<script src="js/script.js"></script>
<script src="js/jquery-3.7.1.min.js"></script>
<script src="js/jquery.maskedinput.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>

