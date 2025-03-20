<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login_page.php");
    exit();
}

require_once "../Backend/db.php";

$name = $_SESSION['username'];

// Fetch user details
$query = "SELECT * FROM users WHERE name = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $name);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found!";
    exit();
}

$message = "";
$message_class = "text-green-600"; // Default success color

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = isset($_POST['name']) ? trim($_POST['name']) : $user['name'];
    $new_email = isset($_POST['email']) ? trim($_POST['email']) : $user['email'];
    $old_password = $_POST['old_password'] ?? "";
    $new_password = $_POST['new_password'] ?? "";
    $profile_pic = $user['profile_pic']; // Keep existing profile picture by default

    // Validate required fields
    if (empty($new_name) || empty($new_email)) {
        $_SESSION['error_message'] = "Error: Name and Email cannot be empty!";
    } else {
        // Check if email already exists for another user
        $email_check_query = "SELECT id FROM users WHERE email = ? AND name != ?";
        $stmt = $conn->prepare($email_check_query);
        $stmt->bind_param("ss", $new_email, $name);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['error_message'] = "Error: Email already exists!";
        } else {
            // Handle profile picture upload
            if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
                $target_dir = "uploads/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                $file_name = time() . "_" . basename($_FILES["profile_pic"]["name"]);
                $target_file = $target_dir . $file_name;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Allow only JPG, PNG, JPEG
                $allowed_types = ["jpg", "jpeg", "png"];
                if (in_array($imageFileType, $allowed_types)) {
                    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
                        $profile_pic = $target_file;
                    } else {
                        $_SESSION['error_message'] = "Error: Unable to upload file!";
                    }
                } else {
                    $_SESSION['error_message'] = "Error: Only JPG, JPEG, and PNG files are allowed!";
                }
            }

            $update_success = false;

            // Handle password update if entered
            if (!empty($old_password) && !empty($new_password)) {
                if (!password_verify($old_password, $user['password'])) {
                    $_SESSION['error_message'] = "Error: Old password is incorrect!";
                } elseif ($old_password === $new_password) {
                    $_SESSION['error_message'] = "Error: New password must be different from the old password!";
                } else {
                    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                    $update_query = "UPDATE users SET name = ?, email = ?, password = ?, profile_pic = ? WHERE name = ?";
                    $stmt = $conn->prepare($update_query);
                    $stmt->bind_param("sssss", $new_name, $new_email, $hashed_password, $profile_pic, $name);
                    $update_success = $stmt->execute();
                }
            } else {
                $update_query = "UPDATE users SET name = ?, email = ?, profile_pic = ? WHERE name = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("ssss", $new_name, $new_email, $profile_pic, $name);
                $update_success = $stmt->execute();
            }

            if ($update_success) {
                $_SESSION['username'] = $new_name;  // ✅ Update session immediately
                $_SESSION['success_message'] = "Profile updated successfully!";
                header("Location: profile.php");
                exit();
            } elseif (!isset($_SESSION['error_message'])) {
                $_SESSION['error_message'] = "Error updating profile!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Law Assist AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white shadow-md py-4 px-8 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-blue-600">Law Assist AI</h1>
        <a href="home_page.php" class="text-blue-600 font-semibold hover:underline">← Back to Home</a>
    </nav>

    <!-- Profile Section -->
    <div class="flex-grow flex items-center justify-center mt-10">
        <div class="bg-white p-10 rounded-xl shadow-lg w-[40rem] border border-gray-300">
            
            <h2 class="text-3xl font-bold text-center text-blue-600 mb-6">My Profile</h2>

            <!-- Success Message -->
            <?php if (!empty($_SESSION['success_message'])) : ?>
                <p class="text-center font-semibold text-green-600"><?= $_SESSION['success_message']; ?></p>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <!-- Error Message -->
            <?php if (!empty($_SESSION['error_message'])) : ?>
                <p class="text-center font-semibold text-red-600"><?= $_SESSION['error_message']; ?></p>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <form action="profile.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div class="text-center">
                    <div class="flex justify-center">
                        <img src="<?= !empty($user['profile_pic']) ? $user['profile_pic'] : 'https://i.imgur.com/UOLOnGk.png' ?>" 
                            alt="Profile Picture" 
                            class="w-40 h-40 rounded-full border-4 border-blue-500 shadow-lg hover:scale-105 transition">
                    </div>
                    <input type="file" name="profile_pic" class="mt-3 text-sm">
                </div>

                <label>Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>" class="w-full px-4 py-3 border rounded-lg">

                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" class="w-full px-4 py-3 border rounded-lg">

                <label>Old Password (if changing)</label>
                <input type="password" name="old_password" class="w-full px-4 py-3 border rounded-lg">

                <label>New Password</label>
                <input type="password" name="new_password" class="w-full px-4 py-3 border rounded-lg">

                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg">Update Profile</button>
            </form>
        </div>
    </div>
</body>
</html>
