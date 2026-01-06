<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['username'])) {
    echo json_encode([
        "logged_in" => false
    ]);
    exit;
}

echo json_encode([
    "logged_in" => true,
    "username" => $_SESSION['username'],
    "role" => $_SESSION['role'] ?? "guest"
]);
