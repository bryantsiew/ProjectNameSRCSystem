<?php
session_start();
include 'database.php';

// 1. Check if user is logged in (using the key from your process_login.php)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Fetch data using the exact column names in your update_process file
$query = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// 3. Set display variables safely
$u_name = htmlspecialchars($user['name'] ?? 'User');
$u_user = htmlspecialchars($user['username'] ?? '');
$u_unit = $user['unit'] ?? '';
$initial = strtoupper(substr($u_name, 0, 1));

// 4. Back Link Logic (Checks the role saved during login)
$back_link = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ? 'admin_post.php' : 'landing_page.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - CNBS</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-body: #f0f2f5; --bg-sidebar: #3e6248; --bg-header: #ffffff;
            --bg-card: #ffffff; --text-main: #111b21; --text-muted: #667781;
            --accent: #3e6248; --border: #e9edef; --input-bg: #f0f2f5;
        }
        body.dark-mode {
            --bg-body: #111b21; --bg-sidebar: #202c33; --bg-header: #202c33;
            --bg-card: #2a3942; --text-main: #e9edef; --text-muted: #8696a0;
            --accent: #00a884; --border: #2a3942; --input-bg: #111111;
        }
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: var(--bg-body); color: var(--text-main); display: flex; transition: 0.3s; }
        .sidebar { width: 260px; background: var(--bg-sidebar); height: 100vh; position: fixed; color: white; display: flex; flex-direction: column; padding: 20px 0; z-index: 100; }
        .brand { font-size: 1.4rem; font-weight: bold; padding: 0 24px 30px; letter-spacing: 1px; }
        .menu-item { padding: 14px 24px; color: rgba(255,255,255,0.7); text-decoration: none; display: flex; align-items: center; gap: 12px; }
        .menu-item:hover, .menu-item.active { background: rgba(0,0,0,0.1); color: white; }
        .main-content { margin-left: 260px; width: calc(100% - 260px); min-height: 100vh; }
        .header { background: var(--bg-header); padding: 15px 40px; display: flex; justify-content: flex-end; align-items: center; gap: 20px; border-bottom: 1px solid var(--border); }
        .card { background: var(--bg-card); margin: 40px auto; max-width: 800px; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .card-header { padding: 30px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 20px; }
        .avatar-circle { width: 60px; height: 60px; background: var(--accent); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; }
        .card-body { padding: 40px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem; color: var(--text-muted); }
        input, select { width: 100%; padding: 12px; margin-bottom: 25px; border-radius: 8px; border: 1px solid var(--border); background: var(--input-bg); color: var(--text-main); font-size: 1rem; }
        .btn { background: var(--accent); color: white; border: none; padding: 14px 28px; border-radius: 8px; font-weight: bold; cursor: pointer; width: 100%; font-size: 1rem; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 8px; text-align: center; font-weight: bold; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body class="<?php echo (isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark') ? 'dark-mode' : ''; ?>">

    <div class="sidebar">
        <div class="brand">CNBS Portal</div>
        <a href="<?php echo $back_link; ?>" class="menu-item"><i class="fas fa-arrow-left"></i> Back to Board</a>
        <a href="edit_profile.php" class="menu-item active"><i class="fas fa-user-edit"></i> Edit Profile</a>
        <div style="flex-grow: 1;"></div>
        <a href="logout.php" class="menu-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h2 style="margin:0; font-size: 1.1rem;">Profile Settings</h2>
            <div id="themeToggle" onclick="toggleTheme()" style="cursor:pointer;"><i class="fas fa-moon"></i></div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="avatar-circle"><?php echo $initial; ?></div>
                <h3 style="margin:0;"><?php echo $u_name; ?></h3>
            </div>

            <div class="card-body">
                <?php if(isset($_GET['status'])): ?>
                    <?php if($_GET['status'] == 'success'): ?>
                        <div class="alert alert-success">Profile updated successfully!</div>
                    <?php elseif($_GET['status'] == 'wrongpass'): ?>
                        <div class="alert alert-danger">Error: Incorrect current password.</div>
                    <?php endif; ?>
                <?php endif; ?>

                <form action="update_profile_process.php" method="POST">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                        <div>
                            <label>Full Name</label>
                            <input type="text" name="name" value="<?php echo $u_name; ?>" required>
                            
                            <label>Username</label>
                            <input type="text" name="username" value="<?php echo $u_user; ?>" required>

                            <label>Unit / Block</label>
                            <select name="unit">
                                <option value="A" <?php echo ($u_unit == 'A') ? 'selected' : ''; ?>>Block A</option>
                                <option value="B" <?php echo ($u_unit == 'B') ? 'selected' : ''; ?>>Block B</option>
                                <option value="C" <?php echo ($u_unit == 'C') ? 'selected' : ''; ?>>Block C</option>
                            </select>
                        </div>

                        <div>
                            <label>New Password (Leave blank to keep same)</label>
                            <input type="password" name="new_pass" placeholder="Enter new password">
                            
                            <hr style="border-color: var(--border); margin: 30px 0; opacity: 0.5;">
                            
                            <label style="color: #ef5350;">🔒 Enter Current Password to Save</label>
                            <input type="password" name="current_pass" required placeholder="Verify current password">

                            <button type="submit" class="btn">Update Profile</button>
                        </div>
                    </div>
                </form>
            </div>
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