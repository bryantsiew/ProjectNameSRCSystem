<?php
include "database.php";
$stmt = mysqli_prepare($conn,
"SELECT * FROM notice WHERE notice_id=?");
mysqli_stmt_bind_param($stmt, "i", $_GET['id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
echo $row['title'];
echo $row['description'];
?>
