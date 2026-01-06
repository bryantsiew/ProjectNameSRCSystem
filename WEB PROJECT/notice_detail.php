<?php
require "database.php";

/* 1. Validate ID */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid notice ID");
}

$notice_id = (int)$_GET['id'];

/* 2. Fetch notice */
$sql = "
    SELECT 
        notice_id,
        title,
        content,
        category,
        priority,
        notice_date,
        created_at
    FROM notices
    WHERE notice_id = ?
    LIMIT 1
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $notice_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    die("Notice not found");
}

$notice = mysqli_fetch_assoc($result);

/* 3. Optional: increase view count */
mysqli_query($conn, "UPDATE notices SET views = views + 1 WHERE notice_id = $notice_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($notice['title']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; background:#f5f5f5; padding:40px; }
        .card { background:white; padding:30px; border-radius:8px; max-width:800px; margin:auto; }
        .meta { color:#777; font-size:14px; margin-bottom:20px; }
        .badge { padding:4px 8px; border-radius:4px; font-size:12px; font-weight:bold; }
        .high { background:#fdecea; color:#d32f2f; }
        .low { background:#e8f5e9; color:#2e7d32; }
    </style>
</head>
<body>

<div class="card">
    <h1><?php echo htmlspecialchars($notice['title']); ?></h1>

    <div class="meta">
        Category: <?php echo htmlspecialchars($notice['category']); ?> |
        Date: <?php echo htmlspecialchars($notice['notice_date']); ?> |
        Priority:
        <span class="badge <?php echo strtolower($notice['priority']); ?>">
            <?php echo htmlspecialchars($notice['priority']); ?>
        </span>
    </div>

    <p><?php echo nl2br(htmlspecialchars($notice['content'])); ?></p>

    <br>
    <a href="landing_page.php">← Back to Home</a>
</div>

<div class="main-content">
    <div class="container">

        <!-- Filter -->
        <div class="card">
    <form id="filterForm">
        <select id="categoryFilter">
            <option value="all">All Categories</option>
            <option value="Maintenance">Maintenance</option>
            <option value="Event">Event</option>
        </select>
        <input type="text" id="searchInput" placeholder="Search notices">
        <button type="submit">Apply</button>
    </form>
</div>


        <!-- Calendar -->
        <div class="card">
    <div class="calendar-header">
        <h3>Event Calendar</h3>
        <button onclick="changeMonth(-1)">‹</button>
        <span id="monthDisplay"></span>
        <button onclick="changeMonth(1)">›</button>
    </div>
    <div class="calendar-grid" id="calendarGrid"></div>
</div>


        <!-- Existing notice card (DO NOT TOUCH) -->
        <div class="card">
            ...
        </div>

    </div>
</div>


</body>
</html>

