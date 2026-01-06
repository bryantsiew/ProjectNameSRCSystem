<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Notices - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #e0e0e0 0%, #f0f2f5 100%);
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-sidebar: rgba(20, 20, 20, 0.9); /* Darker sidebar for admin */
            --glass-border: 1px solid rgba(255, 255, 255, 0.5);
            --text-main: #111b21; --accent: #d32f2f;
            --backdrop: blur(12px);
        }
        body.dark-mode {
            --bg-gradient: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            --glass-bg: rgba(40, 40, 40, 0.8);
            --glass-sidebar: rgba(0, 0, 0, 0.9);
            --glass-border: 1px solid rgba(255, 255, 255, 0.1);
            --text-main: #e9edef; --accent: #ef5350;
        }
        * { box-sizing: border-box; transition: 0.3s; }
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: var(--bg-gradient); color: var(--text-main); display: flex; height: 100vh; }

        .sidebar { width: 260px; background: var(--glass-sidebar); backdrop-filter: var(--backdrop); border-right: var(--glass-border); display: flex; flex-direction: column; color: white; padding: 20px 0; }
        .menu-item { padding: 12px 24px; color: #aaa; text-decoration: none; display: flex; align-items: center; gap: 10px; border-left: 4px solid transparent; }
        .menu-item:hover, .menu-item.active { background-color: rgba(255,255,255,0.1); color: var(--accent); border-left: 4px solid var(--accent); }

        .main-content { flex: 1; padding: 40px; overflow-y: auto; }

        .card { 
            background: var(--glass-bg); backdrop-filter: var(--backdrop);
            padding: 20px; border-radius: 12px; border: var(--glass-border); 
            box-shadow: 0 8px 32px 0 rgba(0,0,0,0.1);
        }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { text-align: left; padding: 15px; background: rgba(0,0,0,0.05); color: var(--text-main); }
        td { padding: 15px; border-bottom: 1px solid rgba(0,0,0,0.1); }
        
        .btn-create { background: var(--accent); color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-weight: bold; float: right; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
        .btn-edit { color: #2196f3; text-decoration: none; font-weight: bold; margin-right: 15px; }
        .btn-delete { color: #ef5350; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2 style="padding: 0 24px; color: var(--accent);">ADMIN PANEL</h2>
        <a href="admin_post.html" class="menu-item"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="manage_notices.html" class="menu-item active"><i class="fas fa-list"></i> Manage Notices</a>
        <div style="flex-grow:1"></div>
        <a href="login.html" class="menu-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 style="margin:0; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Manage Content</h1>
            <a href="admin_post.html" class="btn-create"><i class="fas fa-plus"></i> Create New Notice</a>
        </div>
        
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    </tbody>
            </table>
        </div>
    </div>
    <script>
        if(localStorage.getItem('theme') === 'dark') document.body.classList.add('dark-mode');
    </script>
</body>
</html>