<?php
include "database.php";
$result = mysqli_query($conn, "SELECT * FROM notice");
while ($row = mysqli_fetch_assoc($result)) {
    echo "<h3>".$row['title']."</h3>";
    echo "<p>".$row['description']."</p>";
}
?>

