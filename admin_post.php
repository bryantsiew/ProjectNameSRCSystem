<?php
session_start();
include 'database.php';

// Security: Only allow admins
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: landing_page.php");
    exit();
}

// Fetch current Banner data
$banner_res = mysqli_query($conn, "SELECT setting_value FROM settings WHERE setting_key = 'banner_text'");
$current_banner = mysqli_fetch_assoc($banner_res)['setting_value'] ?? "";

$status_res = mysqli_query($conn, "SELECT setting_value FROM settings WHERE setting_key = 'banner_status'");
$current_status = mysqli_fetch_assoc($status_res)['setting_value'] ?? 'inactive';

// Fetch all notices for the table
$notices_result = mysqli_query($conn, "SELECT * FROM notices ORDER BY notice_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #e0e0e0 0%, #f0f2f5 100%);
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-sidebar: rgba(20, 20, 20, 0.85); 
            --glass-border: 1px solid rgba(255, 255, 255, 0.5);
            --text-main: #111b21; --accent: #d32f2f;
            --backdrop: blur(12px);
        }
        body.dark-mode {
            --bg-gradient: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            --glass-bg: rgba(40, 40, 40, 0.8);
            --glass-sidebar: rgba(0, 0, 0, 0.9);
            --text-main: #e9edef;
        }
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: var(--bg-gradient); color: var(--text-main); display: flex; min-height: 100vh; transition: 0.3s; }
        
        /* Sidebar Styles from admin_post (1).html */
        .sidebar { width: 280px; background: var(--glass-sidebar); backdrop-filter: var(--backdrop); color: white; display: flex; flex-direction: column; padding: 30px 0; position: fixed; height: 100vh; }
        .menu-item { padding: 16px 28px; color: rgba(255,255,255,0.7); text-decoration: none; display: flex; align-items: center; gap: 15px; transition: 0.3s; cursor: pointer; }
        .menu-item:hover, .menu-item.active { background: rgba(255,255,255,0.1); color: var(--accent); border-right: 4px solid var(--accent); }
        
        .main-content { margin-left: 280px; padding: 40px; width: 100%; }
        .card { background: var(--glass-bg); backdrop-filter: var(--backdrop); border: var(--glass-border); padding: 30px; border-radius: 20px; box-shadow: 0 8px 32px rgba(0,0,0,0.1); margin-bottom: 30px; }
        
        /* Table and Form Styles */
        input, select, textarea { width: 100%; padding: 12px; margin: 10px 0; border-radius: 8px; border: 1px solid #ccc; box-sizing: border-box; }
        .btn-submit { background: var(--accent); color: white; border: none; padding: 15px; width: 100%; border-radius: 10px; font-weight: bold; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid rgba(0,0,0,0.05); }
        .action-links a { margin-right: 10px; text-decoration: none; font-weight: bold; }
        .delete-link { color: #d32f2f; }
        .view-link { color: #3e6248; }
    </style>
</head>
<body class="<?php echo (isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark') ? 'dark-mode' : ''; ?>">

    <div class="sidebar">
        <h2 style="padding: 0 24px; color: var(--accent);">ADMIN PANEL</h2>
        <a href="admin_post.php" class="menu-item active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="admin_logs.php" class="menu-item"><i class="fas fa-history"></i> Audit Logs</a>
        <a href="landing_page.php" class="menu-item"><i class="fas fa-eye"></i> View Board</a>
        <div style="flex-grow:1"></div>
        <div class="menu-item" onclick="toggleTheme()"><i class="fas fa-adjust"></i> Toggle Theme</div>
        <a href="logout.php" class="menu-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <h1>Dashboard Overview</h1>

        <div class="card">
            <h3>📢 Emergency Banner Settings</h3>
            <form action="update_banner.php" method="POST">
                <input type="text" name="banner_text" value="<?= htmlspecialchars($current_banner) ?>" placeholder="Enter alert message...">
                <select name="banner_status">
                    <option value="active" <?= ($current_status == 'active') ? 'selected' : '' ?>>Visible (Active)</option>
                    <option value="inactive" <?= ($current_status == 'inactive') ? 'selected' : '' ?>>Hidden (Inactive)</option>
                </select>
                <button type="submit" class="btn-submit">UPDATE BANNER</button>
            </form>
        </div>

        <div class="card">
            <h3>✍️ Create New Notice</h3>
            <form action="post_notice_process.php" method="POST">
                <input type="text" name="title" placeholder="Notice Title" required>
                <div style="display: flex; gap: 20px;">
                    <select name="category">
                        <option value="Event">Event</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="Security">Security</option>
                    </select>
                    <div style="padding-top:15px;">
                        Priority: 
                        <input type="radio" name="prio" value="low" checked style="width:auto;"> Low
                        <input type="radio" name="prio" value="high" style="width:auto; margin-left:15px;"> High
                    </div>
                </div>
                <textarea name="details" rows="5" placeholder="Type message here..."></textarea>
                <button type="submit" class="btn-submit">PUBLISH NOTICE</button>
            </form>
        </div>

        <div class="card">
            <h3>🗂 Manage Existing Notices</h3>
            <table>
                <thead>
                    <tr>
                        <th>Date</th><th>Title</th><th>Category</th><th>Priority</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($notices_result)): ?>
                    <tr>
                        <td><?= $row['notice_date'] ?></td>
                        <td><b><?= htmlspecialchars($row['title']) ?></b></td>
                        <td><?= $row['category'] ?></td>
                        <td><span style="color: <?= ($row['priority'] == 'high') ? '#ef5350' : 'inherit' ?>"><?= ucfirst($row['priority']) ?></span></td>
                        <td class="action-links">
                            <a href="notice_detail.php?id=<?= $row['notice_id'] ?>" class="view-link">View</a>
                            <a href="delete_notice.php?id=<?= $row['notice_id'] ?>" class="delete-link" onclick="return confirm('Confirm Delete?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function toggleTheme() {
            document.body.classList.toggle("dark-mode");
            const isDark = document.body.classList.contains('dark-mode');
            document.cookie = "theme=" + (isDark ? 'dark' : 'light') + ";path=/;max-age=31536000";
        }
    </script>
</body>
</html>