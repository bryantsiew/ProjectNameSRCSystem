<?php
require_once "database.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Invalid request");
}

if (
    empty($_POST["notice_id"]) ||
    empty($_POST["comment_text"]) ||
    empty($_SESSION["user_id"])
) {
    exit("Missing data");
}

$notice_id = (int)$_POST["notice_id"];
$user_id   = (int)$_SESSION["user_id"];
$comment   = trim($_POST["comment_text"]);

$stmt = $conn->prepare(
    "INSERT INTO comments (notice_id, user_id, comment_text)
     VALUES (?, ?, ?)"
);
$stmt->bind_param("iis", $notice_id, $user_id, $comment);
$stmt->execute();

header("Location: notice_detail.html?id=" . $notice_id);
exit;




