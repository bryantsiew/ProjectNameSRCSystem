<?php
require "database.php";

$sql = "
    SELECT 
        timestamp,
        user_id,
        action,
        details,
        ip_address
    FROM audit_logs
    ORDER BY timestamp DESC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) === 0) {
    echo "<tr><td colspan='5'>No audit logs found</td></tr>";
    exit;
}

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['timestamp']}</td>
        <td>{$row['user_id']}</td>
        <td>{$row['action']}</td>
        <td>{$row['details']}</td>
        <td>{$row['ip_address']}</td>
    </tr>";
}



