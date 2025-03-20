<?php
session_start();
$message = "";

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Remove message after displaying
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Law Assist AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="relative h-screen w-screen flex items-center justify-center bg-cover bg-center"
      style="background-image: url('https://www.cnet.com/a/img/resize/810ffd6ceee20a6ab49316101726a09d8e21566e/hub/2023/01/14/d026d715-2a63-4290-8da7-e9a3e2169469/ai-law-legal-books-web.jpg?auto=webp&width=1200');">

    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold text-blue-600 text-center mb-4">Verify OTP</h2>

        <?php if (!empty($message)) : ?>
            <p class="text-center font-semibold text-red-600"><?= $message; ?></p>
        <?php endif; ?>

        <form action="../Backend/verify_otp_process.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700">Enter OTP</label>
                <input type="text" name="otp" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-200">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                Verify OTP
            </button>
        </form>
        <a href="../Frontend/forgot_password.php" class="block text-center text-blue-500 font-semibold hover:underline mt-4">Resend OTP</a>
    </div>
</body>
</html>
