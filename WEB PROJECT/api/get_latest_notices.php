<?php
require "../database.php";
header("Content-Type: application/json");

$sql = "
    SELECT notice_id, title, category, priority, notice_date
    FROM notices
    ORDER BY notice_date DESC
    LIMIT 5
";

$result = mysqli_query($conn, $sql);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
