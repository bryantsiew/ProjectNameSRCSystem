<?php
include "database.php";

if (!isset($_GET['id'])) {
    die("Missing notice ID");
}

$notice_id = (int)$_GET['id'];

$stmt = $conn->prepare("DELETE FROM notices WHERE notice_id = ?");
$stmt->bind_param("i", $notice_id);
$stmt->execute();

header("Location: admin_post.html");
exit;



