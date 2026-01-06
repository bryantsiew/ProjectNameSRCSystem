<?php
session_start();
require "database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Form not submitted correctly");
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === "" || $password === "") {
    die("Missing credentials");
}

/* Get user */
$stmt = mysqli_prepare($conn,
    "SELECT user_id, username, password, role, status 
     FROM users 
     WHERE username = ?"
);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {

    /* Check account status */
    if ($user['status'] !== 'active') {
        die("Account disabled");
    }

    /* Verify hashed password */
    if (!password_verify($password, $user['password'])) {
        die("Invalid username or password");
    }

    /* Create session */
    $_SESSION['user_id']  = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role']     = $user['role'];

    /* Redirect by role */
    if ($user['role'] === 'admin') {
        header("Location: admin_post.html");
    } else {
        header("Location: landing_page.html");
    }
    exit;

} else {
    die("Invalid username or password");
}

