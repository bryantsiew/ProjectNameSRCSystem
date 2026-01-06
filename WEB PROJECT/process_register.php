<?php
include "database.php";

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$stmt = mysqli_prepare($conn,
"INSERT INTO user (username, email, password) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);
mysqli_stmt_execute($stmt);

header("Location: login.php");
exit();
?>


