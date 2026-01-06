<?php
session_start();
session_unset();
session_destroy();
// Redirect back to landing page - it will now show "Login" instead of "Logout"
header("Location: landing_page.php");
exit();
?>