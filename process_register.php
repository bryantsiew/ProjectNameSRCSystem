<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['user']);
    $password = password_hash($_POST['pass'], PASSWORD_DEFAULT); // Encrypts password
    $unit = mysqli_real_escape_string($conn, $_POST['unit']);

    // Check if username exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    
    if (mysqli_num_rows($check) > 0) {
        header("Location: register.php?error=taken");
        exit();
    } else {
        // Inserts user as 'resident' by default
        $sql = "INSERT INTO users (username, password, name, unit, role) 
                VALUES ('$username', '$password', '$full_name', '$unit', 'resident')";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: login.php?status=registered");
            exit();
        } else {
            echo "Registration failed: " . mysqli_error($conn);
        }
    }
}
?>

