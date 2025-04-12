<?php
include "../components/core.php";

if(isset($_GET['id'])) {
    $stmt = $mysqli->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    echo json_encode($result);
}

?>