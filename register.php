<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - CNBS</title>
    <style>
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
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: var(--bg-gradient); height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: var(--glass-bg); backdrop-filter: var(--backdrop); border: var(--glass-border); border-radius: 20px; padding: 40px; width: 350px; box-shadow: var(--shadow); }
        h2 { margin: 0; color: var(--accent); }
        input, select { width: 100%; padding: 12px; margin: 10px 0; border-radius: 8px; border: var(--glass-border); background: rgba(255,255,255,0.5); box-sizing: border-box; }
        .btn { background: var(--accent); color: white; border: none; padding: 12px; width: 100%; border-radius: 8px; cursor: pointer; font-weight: bold; margin-top: 20px; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 1000; }
        .modal-content { background: white; padding: 25px; border-radius: 15px; text-align: center; width: 300px; box-shadow: var(--shadow); color: #333; }
    </style>
</head>
<body class="<?= isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark' ? 'dark-mode' : '' ?>">

    <div id="errorModal" class="modal" style="<?= (isset($_GET['error']) && $_GET['error'] == 'taken') ? 'display:flex' : 'display:none' ?>">
        <div class="modal-content">
            <h3 style="color: #d32f2f;">Username Taken</h3>
            <p>Please choose a different username.</p>
            <button type="button" onclick="closeModal()" class="btn">Try Again</button>
        </div>
    </div>

    <div class="login-card">
        <h2>Join Us</h2>
        <p style="opacity: 0.7; font-size: 0.9em; margin-bottom: 25px;">Create your community account</p>
        
        <form action="process_register.php" method="POST">
            <label style="font-size:0.85em; opacity:0.8;">Full Name</label>
            <input type="text" name="name" required placeholder="Enter your name">

            <label style="font-size:0.85em; opacity:0.8;">Username</label>
            <input type="text" name="user" required placeholder="Choose a username">

            <label style="font-size:0.85em; opacity:0.8;">Password</label>
            <input type="password" name="pass" required placeholder="Create a password">
            
            <label style="font-size:0.85em; opacity:0.8;">Block</label>
            <select name="unit">
                <option value="A">Block A</option>
                <option value="B">Block B</option>
                <option value="C">Block C</option>
            </select>

            <button type="submit" class="btn">REGISTER</button>
        </form>

        <p style="text-align: center; font-size: 0.8em; margin-top: 20px;">
            Already a member? <a href="login.php" style="color: var(--accent); font-weight: bold; text-decoration: none;">Login here</a>
        </p>
    </div>

    <script>
        // ONE SINGLE FUNCTION TO RULE THEM ALL
        function closeModal() {
            // 1. Find the modal and hide it
            const modal = document.getElementById('errorModal');
            if (modal) {
                modal.style.display = 'none';
            }

            // 2. The fix for the error: Clean the URL
            // This works across all modern browsers
            const url = new URL(window.location);
            url.searchParams.delete('error');
            window.history.replaceState({}, '', url);
        }
    </script>
</body>
</html>