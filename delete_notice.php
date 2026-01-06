<?php
include 'database.php';
session_start();

if ($_SESSION['role'] === 'admin' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    mysqli_query($conn, "DELETE FROM notices WHERE notice_id = $id");
}

header("Location: admin_post.php");
exit();
?>

