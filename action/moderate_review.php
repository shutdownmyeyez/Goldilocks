<?php
session_start();
require_once "../components/core.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] !== '3') {
    header('Location: /access_denied.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }

    $review_id = (int)$_POST['review_id'];
    $action = $_POST['action'];

    $allowed_actions = ['approve', 'reject'];
    if (!in_array($action, $allowed_actions)) {
        die("Invalid action");
    }

    $status = $action === 'approve' ? 'approved' : 'rejected';

    $stmt = $mysqli->prepare("UPDATE reviews SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $review_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Отзыв успешно " . ($action === 'approve' ? 'одобрен' : 'отклонен');
    } else {
        $_SESSION['error'] = "Ошибка при обновлении статуса отзыва";
    }

    $stmt->close();
}

header("Location: ../account.php");
exit();
?>