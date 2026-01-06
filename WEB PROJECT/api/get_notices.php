<?php
header("Content-Type: application/json");
require "../database.php";

$sql = "
    SELECT
        notice_id,
        title,
        category,
        priority,
        notice_date
    FROM notices
    ORDER BY created_at DESC
    LIMIT 10
";

$result = mysqli_query($conn, $sql);

$notices = [];

while ($row = mysqli_fetch_assoc($result)) {
    $notices[] = $row;
}

echo json_encode($notices);
