<?php
include 'database.php';
session_start();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

// Fetch all notices for the table
$query = "SELECT * FROM notices ORDER BY notice_date DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Notices - CNBS</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* YOUR EXACT GLASSMORPHISM DESIGN */
        :root {
            --bg-gradient: linear-gradient(135deg, #dcf8c6 0%, #f0f2f5 100%);
            --glass-bg: rgba(255, 255, 255, 0.65);
            --glass-sidebar: rgba(62, 98, 72, 0.85);
            --glass-border: 1px solid rgba(255, 255, 255, 0.5);
            --text-main: #111b21; --text-muted: #54656f; --accent: #3e6248;
            --backdrop: blur(12px); --shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }
        body.dark-mode {
            --bg-gradient: linear-gradient(135deg, #0b141a 0%, #202c33 100%);
            --glass-bg: rgba(32, 44, 51, 0.75);
            --glass-sidebar: rgba(11, 20, 26, 0.95);
            --glass-border: 1px solid rgba(255, 255, 255, 0.08);
            --text-main: #e9edef; --text-muted: #8696a0; --accent: #00a884;
        }
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: var(--bg-gradient); background-attachment: fixed; color: var(--text-main); display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: var(--glass-sidebar); backdrop-filter: blur(10px); color: white; display: flex; flex-direction: column; padding: 20px 0; position: fixed; height: 100vh; border-right: var(--glass-border); }
        .menu-item { padding: 12px 24px; color: rgba(255,255,255,0.8); text-decoration: none; display: flex; align-items: center; gap: 12px; transition: 0.3s; }
        .menu-item:hover, .menu-item.active { background: rgba(255,255,255,0.1); color: white; }
        .main-content { margin-left: 260px; padding: 40px; width: 100%; }
        .card { background: var(--glass-bg); backdrop-filter: var(--backdrop); border: var(--glass-border); border-radius: 16px; padding: 24px; box-shadow: var(--shadow); }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; color: var(--text-muted); font-size: 0.85rem; padding: 12px; }
        td { padding: 16px 12px; border-bottom: 1px solid rgba(0,0,0,0.05); }
        .badge { padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; }
        .badge.high { background: #ffebee; color: #ef5350; }
        .badge.low { background: #e8f5e9; color: #4caf50; }
    </style>
</head>
<body class="<?= isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark' ? 'dark-mode' : '' ?>">

    <div class="sidebar">
        <div style="padding: 0 24px; font-size: 1.2rem; font-weight: bold; margin-bottom: 30px;">CNBS Portal</div>
        
        <a href="landing_page.php" class="menu-item"><i class="fas fa-home"></i> Home</a>
        <a href="notices.php" class="menu-item active"><i class="fas fa-list"></i> All Notices</a>
        
        <div style="flex-grow: 1;"></div>
        
        <?php if ($isLoggedIn): ?>
            <a href="logout.php" class="menu-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
        <?php else: ?>
            <a href="login.php" class="menu-item"><i class="fas fa-sign-in-alt"></i> Login</a>
        <?php endif; ?>
    </div>

    <div class="main-content">
        <h1 style="text-shadow: 0 2px 4px rgba(0,0,0,0.1);">All Notices</h1>
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Title</th>
                        <th>Priority</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= date("M d, Y", strtotime($row['notice_date'])) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td><b><?= htmlspecialchars($row['title']) ?></b></td>
                        <td><span class="badge <?= strtolower($row['priority']) ?>"><?= $row['priority'] ?></span></td>
                        <td><a href="notice_detail.php?id=<?= $row['notice_id'] ?>" style="color:var(--accent); font-weight:bold; text-decoration:none;">View</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        if (document.cookie.split(';').some((item) => item.trim().startsWith('theme=dark'))) {
            document.body.classList.add('dark-mode');
        }
    </script>
</body>
</html>