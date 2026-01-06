<?php
include "database.php";
$stmt = mysqli_prepare($conn,
"UPDATE user SET username=? WHERE user_id=?");
mysqli_stmt_bind_param($stmt, "si",
$_POST['username'], $_POST['user_id']);
mysqli_stmt_execute($stmt);
header("Location: landing_page.php");
?>
