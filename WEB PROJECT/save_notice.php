<?php
include "database.php";

$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';
$category = $_POST['category'] ?? null;
$priority = $_POST['priority'] ?? null;
$notice_date = $_POST['notice_date'] ?? null;

if ($title === '' || $content === '') {
    die("Title and content are required");
}

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO notices (title, content, category, priority, notice_date)
     VALUES (?, ?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "sssss",
    $title,
    $content,
    $category,
    $priority,
    $notice_date
);

mysqli_stmt_execute($stmt);

header("Location: manage_notices.html");
exit();
