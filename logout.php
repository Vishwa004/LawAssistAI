<?php
include 'db.php';
session_start();
session_destroy();
header("Location: ../Frontend/login_page.php"); // Redirect to login page after logout
exit();
?>
