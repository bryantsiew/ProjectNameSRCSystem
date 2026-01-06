<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - CNBS</title>
    <style>
        /* YOUR EXACT CSS FROM login.html */
        :root {
            --bg-gradient: linear-gradient(135deg, #dcf8c6 0%, #f0f2f5 100%);
            --glass-bg: rgba(255, 255, 255, 0.65);
            --glass-border: 1px solid rgba(255, 255, 255, 0.5);
            --text-main: #111b21; --accent: #3e6248;
            --backdrop: blur(12px); --shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }
        body.dark-mode {
            --bg-gradient: linear-gradient(135deg, #0b141a 0%, #202c33 100%);
            --glass-bg: rgba(32, 44, 51, 0.75);
            --glass-border: 1px solid rgba(255, 255, 255, 0.08);
            --text-main: #e9edef; --accent: #00a884;
        }
        body { margin: 0; height: 100vh; display: flex; justify-content: center; align-items: center; font-family: 'Segoe UI', sans-serif; background: var(--bg-gradient); }
        .login-card { background: var(--glass-bg); backdrop-filter: var(--backdrop); border: var(--glass-border); padding: 40px; border-radius: 20px; width: 350px; box-shadow: var(--shadow); text-align: center; }
        input { width: 100%; padding: 12px; margin: 10px 0; border-radius: 8px; border: var(--glass-border); background: rgba(255,255,255,0.5); box-sizing: border-box; }
        .btn { background: var(--accent); color: white; border: none; padding: 12px; width: 100%; border-radius: 8px; cursor: pointer; font-weight: bold; margin-top: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
        .error-msg { color: #d32f2f; font-size: 0.85em; margin-bottom: 10px; font-weight: bold; }
    </style>
</head>
<body class="<?= isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark' ? 'dark-mode' : '' ?>">

    <div class="login-card">
        <h2>CNBS Login</h2>
        <p style="opacity: 0.7; font-size: 0.9em;">Community Notice Board System</p>
        
        <?php if(isset($_GET['error'])): ?>
            <div class="error-msg">Invalid username or password.</div>
        <?php endif; ?>

<form action="process_login.php" method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
     <button type="submit" class="btn">LOG IN</button>
</form>

        <br>
        <p style="font-size: 0.8em;">
            New Resident? <a href="register.php" style="color: var(--accent);">Register Here</a><br><br>
            <a href="landing_page.php" style="color: var(--accent);">← Go to Board</a>
        </p>
    </div>
</body>
</html>