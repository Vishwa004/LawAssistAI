<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $_SESSION['error'] = "No account found with this email!";
        header("Location: ../Frontend/login_page.php");
        exit();
    }

    // Fetch user data
    $stmt->bind_result($id, $name, $hashedPassword);
    $stmt->fetch();

    // Verify password
    if (password_verify($password, $hashedPassword)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $name;
        $_SESSION['login_success'] = "Login Successful! Redirecting...";

        // Redirect back to login page (the frontend handles showing success and redirection)
        header("Location: ../Frontend/login_page.php");
        exit();
    } else {
        $_SESSION['error'] = "Incorrect password!";
        header("Location: ../Frontend/login_page.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>