<?php
session_start();
require_once "../Backend/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    $email = $_SESSION['reset_email'];

    $query = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $new_password, $email);
    $stmt->execute();

    session_destroy();
    header("Location: ../Frontend/login_page.php");
}
?>
