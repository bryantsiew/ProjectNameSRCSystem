<?php
include 'database.php';
session_start();

// 1. Get the Notice ID from the URL
if (!isset($_GET['id'])) {
    header("Location: landing_page.php");
    exit();
}
$notice_id = (int)$_GET['id'];

// 2. Fetch the Notice Details
$stmt = $conn->prepare("SELECT * FROM notices WHERE notice_id = ?");
$stmt->bind_param("i", $notice_id);
$stmt->execute();
$notice = $stmt->get_result()->fetch_assoc();

if (!$notice) {
    die("Notice not found.");
}

// 3. Fetch Comments for this Notice
$comment_stmt = $conn->prepare("
    SELECT c.comment_text, c.created_at, u.username 
    FROM comments c 
    JOIN users u ON c.user_id = u.user_id 
    WHERE c.notice_id = ? 
    ORDER BY c.created_at DESC
");
$comment_stmt->bind_param("i", $notice_id);
$comment_stmt->execute();
$comments_result = $comment_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($notice['title']) ?> - Details</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* YOUR EXACT CSS FROM notice_detail.html */
        :root {
            --bg-gradient: linear-gradient(135deg, #dcf8c6 0%, #f0f2f5 100%);
            --glass-bg: rgba(255, 255, 255, 0.65);
            --glass-sidebar: rgba(62, 98, 72, 0.85);
            --glass-border: 1px solid rgba(255, 255, 255, 0.5);
            --text-main: #111b21; --accent: #3e6248;
            --backdrop: blur(12px); --shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }
        body.dark-mode {
            --bg-gradient: linear-gradient(135deg, #0b141a 0%, #202c33 100%);
            --glass-bg: rgba(32, 44, 51, 0.75); --glass-sidebar: rgba(17, 27, 33, 0.95);
            --glass-border: 1px solid rgba(255, 255, 255, 0.08);
            --text-main: #e9edef; --accent: #00a884;
        }
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: var(--bg-gradient); background-attachment: fixed; color: var(--text-main); display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: var(--glass-sidebar); backdrop-filter: blur(10px); color: white; display: flex; flex-direction: column; padding: 20px 0; position: fixed; height: 100vh; border-right: var(--glass-border); }
        .menu-item { padding: 12px 24px; color: rgba(255,255,255,0.8); text-decoration: none; display: flex; align-items: center; gap: 12px; transition: 0.3s; }
        .menu-item:hover { background: rgba(255,255,255,0.1); color: white; }
        .main-content { margin-left: 260px; padding: 40px; width: 100%; display: flex; justify-content: center; }
        .container { width: 100%; max-width: 800px; }
        .card { background: var(--glass-bg); backdrop-filter: var(--backdrop); border: var(--glass-border); border-radius: 20px; padding: 35px; box-shadow: var(--shadow); }
        .tag { background: var(--accent); color: white; padding: 5px 15px; border-radius: 20px; font-size: 0.8em; font-weight: bold; text-transform: uppercase; }
        
        /* Comment Styles */
        .comment-item { background: rgba(255,255,255,0.2); border-radius: 12px; padding: 15px; margin-bottom: 15px; border-left: 4px solid var(--accent); }
        .comment-user { font-weight: bold; color: var(--accent); font-size: 0.9em; }
        .comment-date { font-size: 0.75em; opacity: 0.6; margin-left: 10px; }
        textarea { width: 100%; padding: 15px; border-radius: 12px; border: var(--glass-border); background: rgba(255,255,255,0.4); font-family: inherit; margin-bottom: 10px; box-sizing: border-box; }
        .btn-comment { background: var(--accent); color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: bold; float: right; }
    </style>
</head>
<body class="<?= isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark' ? 'dark-mode' : '' ?>">

    <div class="sidebar">
        <div style="padding: 0 24px; font-weight: bold; font-size: 1.2em; margin-bottom: 30px;">CNBS Portal</div>
        <a href="landing_page.php" class="menu-item"><i class="fas fa-arrow-left"></i> Back to Board</a>
    </div>

    <div class="main-content">
        <div class="container">
            <div class="card">
                <span class="tag"><?= htmlspecialchars($notice['category']) ?></span>
                <span style="color: var(--text-main); opacity:0.6; font-size: 0.9em; margin-left: 10px;">
                    Posted on <?= date("F j, Y", strtotime($notice['notice_date'])) ?>
                </span>
                
                <h1 style="margin-top: 15px;"><?= htmlspecialchars($notice['title']) ?></h1>
                <hr style="border: 0; border-top: 1px solid rgba(0,0,0,0.1); margin: 20px 0;">
                
                <p style="line-height: 1.7; font-size: 1.05em; white-space: pre-wrap;"><?= htmlspecialchars($notice['content']) ?></p>
            </div>

            <div style="margin-top: 30px;">
                <h3 style="text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Resident Comments</h3>
                
                <div id="comment-list">
                    <?php if ($comments_result->num_rows > 0): ?>
                        <?php while($c = $comments_result->fetch_assoc()): ?>
                            <div class="comment-item">
                                <span class="comment-user">@<?= htmlspecialchars($c['username']) ?></span>
                                <span class="comment-date"><?= date("M d, H:i", strtotime($c['created_at'])) ?></span>
                                <p style="margin: 8px 0 0 0;"><?= htmlspecialchars($c['comment_text']) ?></p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="opacity: 0.6; font-style: italic;">No comments yet. Be the first to share your thoughts!</p>
                    <?php endif; ?>
                </div>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                <form action="post_comment.php" method="POST" style="margin-top: 20px; overflow: hidden;">
                    <input type="hidden" name="notice_id" value="<?= $notice_id ?>">
                    <textarea name="comment_text" rows="3" placeholder="Write a comment..." required></textarea>
                    <button type="submit" class="btn-comment">POST COMMENT</button>
                </form>
                <?php else: ?>
                    <p style="text-align: center; margin-top: 20px; font-size: 0.9em;">
                        Please <a href="login.php" style="color: var(--accent); font-weight: bold;">Login</a> to post a comment.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Check for Dark Mode Cookie
        if (document.cookie.split(';').some((item) => item.trim().startsWith('theme=dark'))) {
            document.body.classList.add('dark-mode');
        }
    </script>
</body>
</html>