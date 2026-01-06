<?php
include "database.php";

/* ---------- UPDATE NOTICE ---------- */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isset($_POST['notice_id'], $_POST['title'], $_POST['content'])) {
        exit("Invalid request");
    }

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE notices 
         SET title = ?, content = ?, category = ?, priority = ?, notice_date = ?
         WHERE notice_id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sssssi",
        $_POST['title'],
        $_POST['content'],
        $_POST['category'],
        $_POST['priority'],
        $_POST['notice_date'],
        $_POST['notice_id']
    );

    mysqli_stmt_execute($stmt);

    header("Location: admin_post.html");
    exit;
}

/* ---------- LOAD NOTICE ---------- */
if (!isset($_GET['id'])) {
    exit("Invalid request");
}

$id = (int) $_GET['id'];

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM notices WHERE notice_id = ?"
);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$notice = mysqli_fetch_assoc($result);

if (!$notice) {
    exit("Notice not found");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Notice</title>
</head>
<body>

<h2>Edit Notice</h2>

<form method="POST">
    <input type="hidden" name="notice_id" value="<?= $notice['notice_id'] ?>">

    <label>Title</label><br>
    <input type="text" name="title" value="<?= htmlspecialchars($notice['title']) ?>" required><br><br>

    <label>Date</label><br>
    <input type="date" name="notice_date" value="<?= $notice['notice_date'] ?>"><br><br>

    <label>Category</label><br>
    <input type="text" name="category" value="<?= $notice['category'] ?>"><br><br>

    <label>Priority</label><br>
    <select name="priority">
        <option <?= $notice['priority']=="Low"?"selected":"" ?>>Low</option>
        <option <?= $notice['priority']=="High"?"selected":"" ?>>High</option>
    </select><br><br>

    <label>Content</label><br>
    <textarea name="content" required><?= htmlspecialchars($notice['content']) ?></textarea><br><br>

    <button type="submit">Update Notice</button>
</form>

</body>
</html>



