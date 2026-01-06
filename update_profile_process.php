<?php
include 'database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_SESSION['user_id'];

    // Only fields that exist in DB
    $username   = mysqli_real_escape_string($conn, $_POST['username']);
    $email      = mysqli_real_escape_string($conn, $_POST['email']);
    $unit_block = mysqli_real_escape_string($conn, $_POST['unit_block']);

    $current_pass = $_POST['current_pass'];
    $new_pass     = $_POST['new_pass'];

    // 1. Get stored password hash
    $res = mysqli_query($conn, "SELECT password FROM users WHERE user_id='$user_id'");
    $user = mysqli_fetch_assoc($res);

    if ($user && password_verify($current_pass, $user['password'])) {

        // 2. Update profile info
        $sql = "
            UPDATE users 
            SET username='$username',
                email='$email',
                unit_block='$unit_block'
            WHERE user_id='$user_id'
        ";
        mysqli_query($conn, $sql);

        // 3. Update password if user entered a new one
        if (!empty($new_pass)) {
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            mysqli_query(
                $conn,
                "UPDATE users SET password='$hashed' WHERE user_id='$user_id'"
            );
        }

        header("Location: edit_profile.php?status=success");
        exit;

    } else {
        header("Location: edit_profile.php?status=wrong_pass");
        exit;
    }
}
?>
