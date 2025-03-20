<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp = trim($_POST['otp']);

    if (!isset($_SESSION['reset_otp']) || !isset($_SESSION['reset_email'])) {
        $_SESSION['message'] = "Session expired. Request a new OTP.";
        header("Location: ../Frontend/forgot_password.php");
        exit();
    }

    if (time() > $_SESSION['otp_expiry']) {
        $_SESSION['message'] = "OTP expired. Request a new OTP.";
        header("Location: ../Frontend/forgot_password.php");
        exit();
    }

    if ($otp == $_SESSION['reset_otp']) {
        $_SESSION['message'] = "OTP verified! Set a new password.";
        header("Location: ../Frontend/reset_password.php");
    } else {
        $_SESSION['message'] = "Invalid OTP!";
        header("Location: ../Frontend/verify_otp.php");
    }
}
?>
