<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate password match
    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: ../Frontend/signup_page.php");
        exit();
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    if (!$stmt) {
        $_SESSION['error'] = "Database error: " . $conn->error;
        header("Location: ../Frontend/signup_page.php");
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Email is already in use!";
        header("Location: ../Frontend/signup_page.php");
        exit();
    }
    $stmt->close();

    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Handle profile picture upload
    $profilePic = NULL; // Default NULL if no image uploaded

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../uploads/";
        $fileName = time() . "_" . basename($_FILES["profile_pic"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFilePath)) {
            $profilePic = $fileName;
        } else {
            $_SESSION['error'] = "Error uploading profile picture.";
            header("Location: ../Frontend/signup_page.php");
            exit();
        }
    }

    // Insert new user into `users` table
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, profile_pic) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        $_SESSION['error'] = "Database error: " . $conn->error;
        header("Location: ../Frontend/signup_page.php");
        exit();
    }

    $stmt->bind_param("ssss", $name, $email, $hashedPassword, $profilePic);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful! Redirecting...";
        header("Location: ../Frontend/signup_page.php");
        exit();
    } else {
        $_SESSION['error'] = "Error during registration!";
        header("Location: ../Frontend/signup_page.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>