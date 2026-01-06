<?php
include "database.php";
$q = "%".$_GET['q']."%";
$stmt = mysqli_prepare($conn,
"SELECT * FROM notice WHERE title LIKE ?");
mysqli_stmt_bind_param($stmt, "s", $q);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['title'];
}
?>
