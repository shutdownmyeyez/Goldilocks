<?php
include "../components/core.php";

if(isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $mysqli->query("DELETE FROM services WHERE id = $id");
    echo "OK";
}
?>