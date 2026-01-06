<?php
include 'database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    // Select the user and their role from the database
    $query = "SELECT * FROM users WHERE username = '$user'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verify password (using password_verify for security)
        if (password_verify($pass, $row['password'])) {
            
            // Set Session variables
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role']; // Save the role (admin or resident)

            // ROLE-BASED REDIRECTION
            if ($row['role'] === 'admin') {
                // Send Admin to their dashboard
                header("Location: admin_post.php");
            } else {
                // Send Regular Residents to the landing page
                header("Location: landing_page.php");
            }
            exit();
            
        } else {
            // Wrong Password
            header("Location: login.php?error=1");
            exit();
        }
    } else {
        // User not found
        header("Location: login.php?error=1");
        exit();
    }
}
?>