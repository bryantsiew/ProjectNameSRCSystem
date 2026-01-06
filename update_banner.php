<?php
include 'database.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_text = mysqli_real_escape_string($conn, $_POST['banner_text']);
    $new_status = mysqli_real_escape_string($conn, $_POST['banner_status']);

    // Update BOTH values
    mysqli_query($conn, "UPDATE settings SET setting_value = '$new_text' WHERE setting_key = 'banner_text'");
    mysqli_query($conn, "UPDATE settings SET setting_value = '$new_status' WHERE setting_key = 'banner_status'");

    // Redirect back to admin_post.php
    header("Location: admin_post.php?status=success");
    exit();
}
?>
