<?php
include 'database.php';
session_start();

$issue = $_POST['issue_text'] ?? '';

if (!empty($issue)) {
    $user_id = $_SESSION['user_id'] ?? 'guest';
    $timestamp = date('Y-m-d H:i:s');

    $sql = "INSERT INTO support_tickets (user_id, issue_text, submitted_at) VALUES ('$user_id', '$issue', '$timestamp')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Your issue has been submitted. Thank you!'); window.location='landing_page.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "<script>alert('Please describe your issue before submitting.'); window.location='help.html';</script>";
}
?>
