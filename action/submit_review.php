<?php
session_start();
require_once '../components/core.php'; // Путь должен быть корректным

// Включить вывод всех ошибок
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Проверка авторизации (используем ваш формат сессии)
if (!isset($_SESSION['user']['id'])) {
    die('Ошибка: Пользователь не авторизован');
}

// Отладочный вывод
echo "<pre>POST: ";
print_r($_POST);
echo "FILES: ";
print_r($_FILES);
echo "</pre>";

// Получаем данные
$user_id = $_SESSION['user']['id'];
$rating = $_POST['rating'];
$comment = $mysqli->real_escape_string($_POST['comment']);

// Вставка отзыва
$query = "INSERT INTO reviews (user_id, comment, rating, status) 
          VALUES ('$user_id', '$comment', '$rating', 'pending')";

if (!$mysqli->query($query)) {
    die("Ошибка при добавлении отзыва: " . $mysqli->error);
}

$review_id = $mysqli->insert_id;
echo "Добавлен отзыв ID: $review_id<br>";

// Обработка изображений
if (!empty($_FILES['images']['tmp_name'][0])) {
    $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/reviews/';
    
    foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
        $fileName = uniqid() . '_' . basename($_FILES['images']['name'][$key]);
        $targetPath = $uploadPath . $fileName;
        
        if (move_uploaded_file($tmpName, $targetPath)) {
            $insertQuery = "INSERT INTO review_images (review_id, file_path) 
                            VALUES ('$review_id', '/uploads/reviews/$fileName')";
            
            if (!$mysqli->query($insertQuery)) {
                echo "Ошибка при добавлении изображения: " . $mysqli->error . "<br>";
            } else {
                echo "Добавлено изображение: $fileName<br>";
            }
        } else {
            echo "Ошибка загрузки файла: " . $_FILES['images']['name'][$key] . "<br>";
        }
    }
} else {
    echo "Нет изображений для загрузки<br>";
}

header('Location: /'); // Закомментируйте редирект для отладки
    ?>