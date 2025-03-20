<?php
session_start();
require_once "../Backend/db.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
require '../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Check if email exists
    $query = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generate a random OTP
        $otp = rand(100000, 999999);

        // Store OTP in session
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_otp'] = $otp;
        $_SESSION['otp_expiry'] = time() + 300; // OTP expires in 5 mins

        // Send email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // SMTP Settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';  // Your SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = 'lawassistai@gmail.com'; // Your email
            $mail->Password   = 'wrof wnal zjsv vqla';  // Your email password or App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Email Content
            $mail->setFrom('lawassistai@gmail.com', 'Law Assist AI');
            $mail->addAddress($email);
            $mail->Subject = "Password Reset Verification Code";

            // Email Body
            $mail->isHTML(true);
            $mail->Body = "
                <html>
                <head>
                    <style>
                        .email-container {
                            font-family: Arial, sans-serif;
                            background-color: #f4f4f4;
                            padding: 20px;
                            border-radius: 10px;
                            text-align: center;
                            max-width: 500px;
                            margin: auto;
                        }
                        .otp-code {
                            font-size: 22px;
                            font-weight: bold;
                            color: #2d89ef;
                            margin: 10px 0;
                        }
                        .footer {
                            font-size: 12px;
                            color: #777;
                            margin-top: 20px;
                        }
                    </style>
                </head>
                <body>
                    <div class='email-container'>
                        <h2>🔐 Password Reset Request</h2>
                        <p>Hello,</p>
                        <p>You have requested to reset your password for <strong>Law Assist AI</strong>. Please use the following One-Time Password (OTP) to verify your request:</p>
                        <p class='otp-code'>$otp</p>
                        <p>This code is valid for <strong>5 minutes</strong>. Do not share it with anyone.</p>
                        <p>If you did not request this, please ignore this email.</p>
                        <p class='footer'>© 2025 Law Assist AI | Need help? <a href='mailto:support@lawassistai.com'>Contact Support</a></p>
                    </div>
                </body>
                </html>
            ";

            // Send email
            $mail->send();
            $_SESSION['message'] = "Verification code sent to your email.";
            header("Location: ../Frontend/verify_otp.php");
            exit();
        } catch (Exception $e) {
            $_SESSION['message'] = "Email sending failed: " . $mail->ErrorInfo;
            header("Location: ../Frontend/forgot_password.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Email not found!";
        header("Location: ../Frontend/forgot_password.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>