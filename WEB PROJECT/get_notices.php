<?php
include "database.php";

$result = mysqli_query($conn,
    "SELECT notice_id, title, category, priority, notice_date, created_at
     FROM notices
     ORDER BY created_at DESC"
);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

header("Content-Type: application/json");
echo json_encode($data);

