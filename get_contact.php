<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Validation: Ensure all fields are filled
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: ../Frontend/contact.php");
        exit();
    }

    // Insert message into database
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        $_SESSION['error'] = "Database error: " . $conn->error;
        header("Location: ../Frontend/contact.php");
        exit();
    }

    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Your message has been sent!";
    } else {
        $_SESSION['error'] = "Error submitting message!";
    }

    $stmt->close();
    $conn->close();
    header("Location: ../Frontend/contact.php");
    exit();
}
?>
