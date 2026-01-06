<?php
header("Content-Type: application/json");
require_once "../database.php";

/*
settings table structure:
- setting_key
- setting_value

Expected keys:
- banner_text
- banner_status
*/

$sql = "
    SELECT setting_key, setting_value
    FROM settings
    WHERE setting_key IN ('banner_text', 'banner_status')
";

$result = mysqli_query($conn, $sql);

$banner = [
    "text" => "",
    "status" => "inactive"
];

while ($row = mysqli_fetch_assoc($result)) {
    if ($row['setting_key'] === 'banner_text') {
        $banner['text'] = $row['setting_value'];
    }
    if ($row['setting_key'] === 'banner_status') {
        $banner['status'] = $row['setting_value'];
    }
}

echo json_encode($banner);
