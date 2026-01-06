<?php
include 'database.php';
session_start();

// Security: Only allow admins to post
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Capture data from your form
    // We use mysqli_real_escape_string to prevent SQL injection (security)
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $priority = mysqli_real_escape_string($conn, $_POST['prio']); // Matches your radio button name
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $date = date('Y-m-d'); // Automatically set today's date

    // 2. Insert into the 'notices' table
    $query = "INSERT INTO notices (title, content, notice_date, category, priority) 
              VALUES ('$title', '$content', '$date', '$category', '$priority')";

    if (mysqli_query($conn, $query)) {
        // 3. Success! Send back to admin page with a status message
        header("Location: admin_post.php?status=posted");
        exit();
    } else {
        // If it fails, show the error (useful for debugging)
        echo "Error: " . mysqli_error($conn);
    }
}
?>