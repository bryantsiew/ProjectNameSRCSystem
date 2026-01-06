<?php
session_start();
include 'database.php';

// 1. Security Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// 2. Fetch Audit Logs with username
$logs_query = "
    SELECT 
        a.timestamp,
        a.action,
        a.details,
        a.ip_address,
        u.username
    FROM audit_logs a
    LEFT JOIN users u ON a.user_id = u.user_id
    ORDER BY a.timestamp DESC
";

$logs_result = mysqli_query($conn, $logs_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Audit Logs - Admin</title>
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
        
        /* Sidebar Navigation */
        .sidebar { width: 280px; background: var(--glass-sidebar); backdrop-filter: var(--backdrop); color: white; display: flex; flex-direction: column; padding: 30px 0; position: fixed; height: 100vh; z-index: 100; }
        .sidebar h2 { padding: 0 24px; margin-bottom: 30px; font-size: 1.5rem; letter-spacing: 2px; }
        .menu-item { padding: 16px 28px; color: rgba(255,255,255,0.7); text-decoration: none; display: flex; align-items: center; gap: 15px; transition: 0.3s; }
        .menu-item:hover, .menu-item.active { background: rgba(255,255,255,0.1); color: var(--accent); border-right: 4px solid var(--accent); }
        .spacer { flex-grow: 1; }

        /* Main Layout */
        .main-content { margin-left: 280px; padding: 40px; width: calc(100% - 280px); }
        h1 { margin-top: 0; font-weight: 700; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }

        /* Log Table Styles */
        .card { background: var(--glass-bg); backdrop-filter: var(--backdrop); border: var(--glass-border); padding: 30px; border-radius: 20px; box-shadow: 0 8px 32px rgba(0,0,0,0.1); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px; color: var(--accent); border-bottom: 2px solid var(--accent); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; }
        td { padding: 15px; border-bottom: 1px solid rgba(0,0,0,0.05); font-size: 0.95rem; }
        .badge { background: rgba(0,0,0,0.05); padding: 4px 10px; border-radius: 6px; font-family: monospace; font-size: 0.85rem; }
        body.dark-mode .badge { background: rgba(255,255,255,0.1); }
    </style>
</head>
<body class="<?php echo (isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark') ? 'dark-mode' : ''; ?>">

    <div class="sidebar">
        <h2 style="color: var(--accent);">ADMIN PANEL</h2>
        <a href="admin_post.php" class="menu-item"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="admin_logs.php" class="menu-item active"><i class="fas fa-history"></i> Audit Logs</a>
        <a href="landing_page.php" class="menu-item"><i class="fas fa-eye"></i> View Board</a>
        
        <div class="spacer"></div>
        
        <div class="menu-item" onclick="toggleTheme()" style="cursor:pointer;"><i class="fas fa-adjust"></i> Toggle Theme</div>
        <a href="logout.php" class="menu-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <h1>System Audit Logs</h1>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Time (MYT)</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Details</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($logs_result) > 0): ?>
                        <?php while($log = mysqli_fetch_assoc($logs_result)): ?>
                        <tr>
                            <td><?php echo date('M d, Y H:i', strtotime($log['timestamp'])); ?></td>
                            <td><strong><?php echo htmlspecialchars($log['username'] ?? 'System'); ?></strong></td>
                            <td><span class="badge"><?php echo strtoupper($log['action']); ?></span></td>
                            <td><?php echo htmlspecialchars($log['details']); ?></td>
                            <td><code><?php echo $log['ip_address']; ?></code></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center; padding: 40px; opacity: 0.5;">No system logs found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function toggleTheme() {
            document.body.classList.toggle("dark-mode");
            const isDark = document.body.classList.contains('dark-mode');
            // Save theme to cookie for PHP to read on reload
            document.cookie = "theme=" + (isDark ? 'dark' : 'light') + ";path=/;max-age=31536000";
        }
    </script>
</body>
</html>

