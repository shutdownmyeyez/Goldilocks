<?php
include "../components/core.php";

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
    $id = (int)$_POST['id'];
    $name = $mysqli->real_escape_string($_POST['name']);
    $description = $mysqli->real_escape_string($_POST['description']);
    $price = (int)$_POST['price'];
    
    // Обработка изображения
    $image = $_FILES['image']['name'] ? 
              uniqid().'.'.pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION) : 
              $mysqli->real_escape_string($_POST['current_image']);
    
    if($_FILES['image']['name']) {
        move_uploaded_file($_FILES['image']['tmp_name'], "../img/services/".$image);
    }

    $mysqli->query("UPDATE services SET 
        name = '$name',
        description = '$description',
        price = $price,
        image = '$image'
        WHERE id = $id
    ");
    
    echo "OK";
}