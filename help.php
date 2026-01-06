<?php
include 'database.php';
session_start();

// Security: If not logged in, redirect to landing page
if (!isset($_SESSION['user_id'])) {
    header("Location: landing_page.php");
    exit();
}
$isLoggedIn = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Help & Support - CNBS</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
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
            --text-main: #e9edef; --accent: #00a884;
        }
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: var(--bg-gradient); display: flex; height: 100vh; overflow: hidden; }
        
        .sidebar { width: 260px; background: var(--glass-sidebar); backdrop-filter: var(--backdrop); color: white; display: flex; flex-direction: column; padding: 20px 0; border-right: var(--glass-border); }
        .brand { padding: 0 24px; font-size: 1.2rem; font-weight: bold; margin-bottom: 30px; }
        .menu-item { padding: 12px 24px; color: rgba(255,255,255,0.7); text-decoration: none; display: flex; align-items: center; gap: 12px; transition: 0.3s; }
        .menu-item:hover, .menu-item.active { background: rgba(255,255,255,0.15); color: white; border-left: 4px solid white; }

        .main-content { flex: 1; padding: 40px; overflow-y: auto; }
        .card { background: var(--glass-bg); backdrop-filter: var(--backdrop); border: var(--glass-border); border-radius: 20px; padding: 35px; box-shadow: var(--shadow); max-width: 800px; margin: 0 auto; }
        
        h2, h3 { color: var(--accent); margin-top: 0; }
        .contact-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(0,0,0,0.05); }
        .label { font-weight: bold; color: var(--text-muted); }
        .value { color: var(--text-main); font-weight: 600; }

        textarea { width: 100%; padding: 15px; border-radius: 12px; border: 1px solid rgba(0,0,0,0.1); background: rgba(255,255,255,0.5); margin-top: 10px; font-family: inherit; }
        .btn { width: 100%; padding: 14px; background: var(--accent); color: white; border: none; border-radius: 10px; font-weight: bold; cursor: pointer; margin-top: 15px; transition: 0.3s; }
        .btn:hover { opacity: 0.9; transform: translateY(-2px); }
    </style>
</head>
<body class="<?= isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark' ? 'dark-mode' : '' ?>">

    <div class="sidebar">
        <div class="brand"><i class="fas fa-city"></i> CNBS Portal</div>
        <a href="landing_page.php" class="menu-item"><i class="fas fa-home"></i> Dashboard</a>
        <a href="edit_profile.php" class="menu-item"><i class="fas fa-user-edit"></i> Edit Profile</a>
        <a href="help.php" class="menu-item active"><i class="fas fa-question-circle"></i> Help</a>
        <div style="flex-grow: 1;"></div>
        <a href="logout.php" class="menu-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="card">
            <h2>Help & Support</h2>
            <p>Residential Support Centre: <b>Level 1, Block B</b>.</p>
            
            <h3><i class="fas fa-phone-alt"></i> Emergency Hotlines (24/7)</h3>
            <div class="contact-row">
                <span class="label">Main Guardhouse:</span>
                <span class="value">+603-8800-1111</span>
            </div>
            <div class="contact-row">
                <span class="label">Police / Ambulance:</span>
                <span class="value">999</span>
            </div>

            <h3 style="margin-top: 30px;"><i class="fas fa-envelope-open-text"></i> Report an Issue</h3>
            <form action="process_support.php" method="POST">
                <textarea name="issue" rows="4" placeholder="Describe your maintenance issue or system problem here..."></textarea>
                <button type="submit" class="btn">SUBMIT REPORT</button>
            </form>
        </div>
    </div>
</body>
</html>