<?php
require_once "../database.php";

header("Content-Type: application/json");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(["error" => "Invalid notice ID"]);
    exit;
}

$noticeId = (int) $_GET['id'];

$sql = "
    SELECT 
        notice_id,
        title,
        content,
        category,
        priority,
        notice_date
    FROM notices
    WHERE notice_id = ?
    LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $noticeId);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["error" => "Notice not found"]);
    exit;
}

echo json_encode($result->fetch_assoc());

