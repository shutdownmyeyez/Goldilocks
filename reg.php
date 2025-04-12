<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/popUp.js"></script>
    <title>Регистрация - Goldilocks</title>
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
                <h2 class="form-title">Регистрация</h2>
                <form id="registrationForm" method="post" action="action/regform.php">
                    <div class="form-group">
                        <label for="fullname">ФИО:</label>
                        <input type="text" id="fullname" name="fullname" required 
                               pattern="[А-ЯЁа-яё]+\s[А-ЯЁа-яё]+\s[А-ЯЁа-яё]+"
                               title="Введите полное ФИО через пробел">
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Телефон:</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>

                    <div class="form-group">
                        <label for="login">Логин:</label>
                        <input type="text" id="login" name="login" required 
                               minlength="4"
                               pattern="[A-Za-z0-9]+">
                    </div>

                    <div class="form-group">
                        <label for="password" >Пароль:</label>
                        <input type="password" id="password" name="password" required
                               minlength="8"
                               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                               title="Минимум 8 символов, 1 цифра, 1 заглавная и строчная буква">
                    </div>
                    <button type="submit" name="submit-btn" class="submit-btn" >Зарегистрироваться</button>
                </form>
                <a href="signUp.php">Уже зарегистированы?</a>
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
