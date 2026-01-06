<?php
// update_banner.php
require_once "database.php";

// Only allow POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Method Not Allowed");
}

// Validate input
if (!isset($_POST["banner_text"])) {
    exit("Missing banner text");
}

$bannerText = trim($_POST["banner_text"]);

// Prevent empty overwrite
if ($bannerText === "") {
    exit("Banner text cannot be empty");
}

// Update banner text
$stmt = $conn->prepare("
    UPDATE settings
    SET setting_value = ?
    WHERE setting_key = 'banner_text'
");
$stmt->bind_param("s", $bannerText);
$stmt->execute();

// Optional: ensure banner is active
$conn->query("
    UPDATE settings
    SET setting_value = 'active'
    WHERE setting_key = 'banner_status'
");

// Redirect back to admin dashboard
header("Location: admin_post.html");
exit;
