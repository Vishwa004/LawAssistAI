<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Law Assist AI - Signup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-cover bg-center" style="background-image: url('https://www.cnet.com/a/img/resize/810ffd6ceee20a6ab49316101726a09d8e21566e/hub/2023/01/14/d026d715-2a63-4290-8da7-e9a3e2169469/ai-law-legal-books-web.jpg?auto=webp&width=1200');">

    <div class="bg-white bg-opacity-90 p-8 rounded-lg shadow-lg w-96 border border-purple-300 relative">
        <div class="flex justify-center mb-4">
            <img src="https://i.imgur.com/UOLOnGk.png" alt="Law Assist AI Logo" class="w-16 h-16">
        </div>
        <h2 class="text-center text-2xl font-bold text-blue-600">Law Assist AI</h2>

        <!-- Display Error Message -->
        <?php if (isset($_SESSION['error'])): ?>
            <p class="bg-red-100 text-red-600 text-center font-semibold p-2 rounded-md mt-2">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </p>
        <?php endif; ?>

        <!-- Display Success Message & Auto Redirect -->
        <?php if (isset($_SESSION['success'])): ?>
            <div id="successMessage" class="fixed top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg text-lg font-semibold">
                <?php echo $_SESSION['success']; ?>
            </div>

            <script>
                setTimeout(() => {
                    window.location.href = '../Frontend/login_page.php'; // Redirect to login page
                }, 2000); // Redirect after 2 seconds
            </script>

            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form class="mt-4" action="../Backend/register.php" method="POST">
            <label class="block text-sm font-semibold">Full Name</label>
            <input type="text" name="name" placeholder="Enter your full name" class="w-full p-2 mt-1 border rounded-md bg-gray-100" required>
            
            <label class="block mt-3 text-sm font-semibold">Email address</label>
            <input type="email" name="email" placeholder="ex:vishwa@gmail.com" class="w-full p-2 mt-1 border rounded-md bg-gray-100" required>
            
            <label class="block mt-3 text-sm font-semibold">Password</label>
            <input type="password" name="password" placeholder="Enter your Password" class="w-full p-2 mt-1 border rounded-md bg-gray-100" required>
            
            <label class="block mt-3 text-sm font-semibold">Confirm Password</label>
            <input type="password" name="confirm_password" placeholder="Re-enter the password" class="w-full p-2 mt-1 border rounded-md bg-gray-100" required>
            
            <button type="submit" class="w-full mt-4 p-2 bg-blue-900 text-white rounded-md text-lg font-semibold hover:bg-blue-800">
                Signup now
            </button>
        </form>

        <!-- Already have an account? Login link -->
        <div class="text-center mt-4">
            <span class="text-gray-600">Already have an account? </span>
            <a href="../Frontend/login_page.php" class="text-blue-600 font-semibold hover:underline">Login</a>
        </div>
    </div>

</body>
</html>
