<?php
include 'database.php';
session_start();

// Security check: redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 1. Fetch Banner Settings
$banner_res = mysqli_query($conn, "SELECT setting_value FROM settings WHERE setting_key = 'banner_text'");
$banner_text = mysqli_fetch_assoc($banner_res)['setting_value'] ?? "Welcome to CNBS Portal";

$status_res = mysqli_query($conn, "SELECT setting_value FROM settings WHERE setting_key = 'banner_status'");
$banner_status = mysqli_fetch_assoc($status_res)['setting_value'] ?? 'inactive';

// 2. Fetch Latest 5 Notices for the Table
$notices_result = mysqli_query($conn, "SELECT * FROM notices ORDER BY notice_date DESC LIMIT 5");

// 3. Fetch All Notice Dates for the Calendar dots
$events_res = mysqli_query($conn, "SELECT notice_id, notice_date FROM notices");
$events_data = [];
while($ev = mysqli_fetch_assoc($events_res)) {
    $events_data[] = ['date' => $ev['notice_date'], 'id' => $ev['notice_id']];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Community Board - Home</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* YOUR EXACT CSS FROM landing_page.html */
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
            --glass-sidebar: rgba(11, 20, 26, 0.9);
            --glass-border: 1px solid rgba(255, 255, 255, 0.08);
            --text-main: #e9edef; --text-muted: #8696a0; --accent: #00a884;
        }
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: var(--bg-gradient); color: var(--text-main); display: flex; min-height: 100vh; transition: 0.5s; }
        
        /* Sidebar */
        .sidebar { width: 260px; background: var(--glass-sidebar); backdrop-filter: var(--backdrop); color: white; display: flex; flex-direction: column; padding: 20px 0; position: fixed; height: 100vh; box-shadow: 4px 0 15px rgba(0,0,0,0.1); z-index: 1000; }
        .brand { font-size: 1.5rem; font-weight: bold; padding: 0 24px 30px; letter-spacing: 1px; color: #fff; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 20px; }
        .menu-item { padding: 14px 24px; color: rgba(255,255,255,0.8); text-decoration: none; display: flex; align-items: center; gap: 12px; transition: 0.3s; font-size: 0.95rem; }
        .menu-item:hover, .menu-item.active { background: rgba(255,255,255,0.1); color: white; border-left: 4px solid #fff; }
        
        /* Main Content */
        .main-content { margin-left: 260px; padding: 40px; width: calc(100% - 260px); }
        .banner { background: var(--accent); color: white; padding: 20px; border-radius: 15px; margin-bottom: 30px; display: flex; align-items: center; gap: 15px; box-shadow: var(--shadow); }
        
        /* Dashboard Grid */
        .dashboard-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
        .card { background: var(--glass-bg); backdrop-filter: var(--backdrop); border: var(--glass-border); border-radius: 20px; padding: 25px; box-shadow: var(--shadow); }
        
        /* Table */
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { text-align: left; color: var(--text-muted); font-size: 0.85rem; padding: 10px; border-bottom: 1px solid rgba(0,0,0,0.05); }
        td { padding: 15px 10px; border-bottom: 1px solid rgba(0,0,0,0.05); font-size: 0.95rem; }
        .badge { padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: bold; }
        .badge-maintenance { background: #e3f2fd; color: #1e88e5; }
        
        /* Calendar UI */
        .calendar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px; text-align: center; }
        .calendar-day { padding: 10px; font-size: 0.85rem; border-radius: 8px; cursor: pointer; position: relative; }
        .calendar-day:hover { background: rgba(0,0,0,0.05); }
        .event-dot { width: 5px; height: 5px; background: var(--accent); border-radius: 50%; position: absolute; bottom: 5px; left: 50%; transform: translateX(-50%); }
    </style>
</head>
<body class="<?= isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark' ? 'dark-mode' : '' ?>">

    <div class="sidebar">
        <div class="brand"><i class="fas fa-leaf"></i> CNBS Portal</div>
        
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="admin_post.php" class="menu-item"><i class="fas fa-arrow-left"></i> Back to Board</a>
        <?php else: ?>
            <a href="landing_page.php" class="menu-item active"><i class="fas fa-home"></i> Home</a>
        <?php endif; ?>

        <a href="notices.php" class="menu-item"><i class="fas fa-bullhorn"></i> All Notices</a>
        <a href="edit_profile.php" class="menu-item"><i class="fas fa-user"></i> My Profile</a>
        <div style="flex:1"></div>
        <div class="menu-item" onclick="toggleTheme()" style="cursor:pointer;"><i class="fas fa-moon"></i> Dark Mode</div>
        <a href="logout.php" class="menu-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <?php if($banner_status == 'active'): ?>
        <div class="banner">
            <i class="fas fa-info-circle fa-lg"></i>
            <div>
                <strong>System Notice:</strong> <?= htmlspecialchars($banner_text) ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="dashboard-grid">
            <div class="card">
                <h3><i class="fas fa-clock"></i> Recent Announcements</h3>
                <table>
                    <thead>
                        <tr><th>DATE</th><th>TITLE</th><th>CATEGORY</th></tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($notices_result)): ?>
                        <tr onclick="window.location.href='notice_detail.php?id=<?= $row['notice_id'] ?>'" style="cursor:pointer;">
                            <td style="color: var(--text-muted);"><?= date('M d', strtotime($row['notice_date'])) ?></td>
                            <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
                            <td><span class="badge badge-maintenance"><?= $row['category'] ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <div class="calendar-header">
                    <button onclick="changeMonth(-1)" style="border:none; background:none; cursor:pointer;"><i class="fas fa-chevron-left"></i></button>
                    <h4 id="monthDisplay" style="margin:0;"></h4>
                    <button onclick="changeMonth(1)" style="border:none; background:none; cursor:pointer;"><i class="fas fa-chevron-right"></i></button>
                </div>
                <div class="calendar-grid" id="calendarGrid"></div>
            </div>
        </div>
    </div>

    <script>
        // Calendar Logic with PHP Data
        let nav = 0;
        const eventsFromDB = <?= json_encode($events_data) ?>;

        function loadCalendar() {
            const dt = new Date();
            if (nav !== 0) dt.setMonth(new Date().getMonth() + nav);

            const day = dt.getDate();
            const month = dt.getMonth();
            const year = dt.getFullYear();

            const firstDayOfMonth = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            document.getElementById('monthDisplay').innerText = dt.toLocaleDateString('en-us', { month: 'long', year: 'numeric' });
            const calendar = document.getElementById('calendarGrid');
            calendar.innerHTML = '';

            for(let i = 0; i < firstDayOfMonth; i++) {
                calendar.appendChild(document.createElement('div'));
            }

            for(let i = 1; i <= daysInMonth; i++) {
                const dayDiv = document.createElement('div');
                dayDiv.className = 'calendar-day';
                dayDiv.innerText = i;
                const dateString = `${year}-${(month + 1).toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`;
                const event = eventsFromDB.find(e => e.date === dateString);

                if(event) {
                    const dot = document.createElement('div');
                    dot.className = 'event-dot';
                    dayDiv.appendChild(dot);
                    dayDiv.onclick = () => window.location.href = `notice_detail.php?id=${event.id}`;
                }
                calendar.appendChild(dayDiv);
            }
        }

        function changeMonth(direction) {
            nav += direction;
            loadCalendar();
        }

        function toggleTheme() {
            document.body.classList.toggle("dark-mode");
            document.cookie = "theme=" + (document.body.classList.contains('dark-mode') ? 'dark' : 'light') + ";path=/";
        }

        loadCalendar();
    </script>
</body>
</html>