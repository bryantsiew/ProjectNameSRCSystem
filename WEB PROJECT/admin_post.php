<?php
include "database.php";
session_start();

/*
EXPECTED FROM admin_post.html (POST):
-----------------------------------
title
content   (or description)
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Method Not Allowed");
}

if (!isset($_POST['title'], $_POST['content'])) {
    http_response_code(400);
    exit("Missing required fields");
}

$title = trim($_POST['title']);
$content = trim($_POST['content']);
$admin = $_SESSION['username'] ?? 'admin';
$ip = $_SERVER['REMOTE_ADDR'];

if ($title === "" || $content === "") {
    http_response_code(400);
    exit("Empty input not allowed");
}

/* INSERT NOTICE */
$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO notice (title, description) VALUES (?, ?)"
);
mysqli_stmt_bind_param($stmt, "ss", $title, $content);
mysqli_stmt_execute($stmt);

/* LOG ADMIN ACTION */
$logStmt = mysqli_prepare(
    $conn,
    "INSERT INTO admin_logs (user, action, details, ip_address)
     VALUES (?, 'POST_NOTICE', ?, ?)"
);
$details = "Posted notice: " . $title;
mysqli_stmt_bind_param($logStmt, "sss", $admin, $details, $ip);
mysqli_stmt_execute($logStmt);

/* REDIRECT BACK */
header("Location: admin_post.html?success=1");
exit();
