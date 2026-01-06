<?php
session_start();
include "database.php";

$title = $_POST['title'];
$content = $_POST['details']; // Matches your admin_post.html textarea name
$category = $_POST['category'];
$priority = $_POST['prio'];
$notice_date = $_POST['date'];

$stmt = $conn->prepare("INSERT INTO notices (title, content, category, priority, notice_date) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $title, $content, $category, $priority, $notice_date);

if ($stmt->execute()) {
    // LOG ACTION
    $user = $_SESSION['username'] ?? 'Admin';
    $log = $conn->prepare("INSERT INTO audit_logs (username, action, details, ip_address) VALUES (?, 'Create', ?, ?)");
    $msg = "Created notice: $title";
    $ip = $_SERVER['REMOTE_ADDR'];
    $log->bind_param("sss", $user, $msg, $ip);
    $log->execute();
}

header("Location: admin_post.php");