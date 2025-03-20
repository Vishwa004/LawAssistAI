<?php 
session_start(); 
if (!isset($_SESSION['username'])) {
    header("Location: login_page.html"); // Redirect if not logged in
    exit();
}
?>

<!DOCTYPE html>



<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Law Assist AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100 flex flex-col min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white shadow-md py-4 px-8 flex justify-between items-center">
        <!-- Logo -->
        <div class="flex items-center space-x-2">
            <img src="https://i.imgur.com/UOLOnGk.png" alt="Logo" class="w-8 h-8">
            <h1 class="text-xl font-bold text-blue-600">Law A<span class="text-blue-400">ssist AI</span></h1>
        </div>
        
        <!-- Navigation Links -->
        <div class="hidden md:flex space-x-6 text-gray-600">
            <a href="../Frontend/home_page.php" class="text-gray-700 hover:text-black font-bold border-b-2 border-black">Home</a>
            <a href="../Frontend/law_page.html">Laws</a>
            <a href="about.html">About Us</a>
            <a href="../Frontend/contact.php">Contact Us</a>
        </div>

        <!-- User Profile Dropdown -->
        <div class="relative">
            <button id="profileBtn" class="flex items-center space-x-2 bg-gray-200 px-4 py-2 rounded-full">
                <span class="font-semibold">
                    <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : "Guest"; ?>
                </span>
                <span class="text-sm">▼</span>
            </button>

            <!-- Dropdown Menu (Initially Hidden) -->
            <div id="dropdownMenu" class="absolute right-0 mt-2 w-40 bg-white shadow-md rounded-lg hidden">
                <a href="profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">My Profile</a>
                <a href="../Backend/logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="flex-grow flex flex-col items-center justify-center">
       <h2></h2>
        <iframe src="http://localhost:8000/" width="100%" height="700px" frameborder="0"></iframe>
        
        
        <!-- Search Bar -->
        <!-- Chat Section -->

    </div>

    <!-- Footer -->
    <footer class="bg-white text-gray-600 py-4 text-center shadow-md mt-auto">
    <div class="container mx-auto">
        <p class="text-sm">© 2025 Law Assist AI. All rights reserved.</p>
        <div class="flex justify-center space-x-4 mt-2">
            <a href="privacy.html" class="text-blue-500 hover:underline">Privacy Policy</a>
            <span>|</span>
            <a href="terms.html" class="text-blue-500 hover:underline">Terms of Service</a>
        </div>
    </div>
</footer>


    <script>
        // Toggle dropdown menu
        document.getElementById("profileBtn").addEventListener("click", function() {
            const dropdown = document.getElementById("dropdownMenu");
            dropdown.classList.toggle("hidden");
        });

        // Close dropdown if clicked outside
        document.addEventListener("click", function(event) {
            const profileBtn = document.getElementById("profileBtn");
            const dropdown = document.getElementById("dropdownMenu");
            
            if (!profileBtn.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add("hidden");
            }
        });
    </script>
    <script>
    document.getElementById("sendBtn").addEventListener("click", function() {
        let userMessage = document.getElementById("userInput").value.trim();
        let chatResponse = document.getElementById("chatResponse");

        if (userMessage === "") return; // Prevent empty messages

        // Display loading state
        chatResponse.textContent = "Thinking...";
        chatResponse.classList.remove("hidden");

        // API Request (Modify the API URL)
        fetch("https:///query", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ message: userMessage })
        })
        .then(response => response.json())
        .then(data => {
            chatResponse.textContent = data.response || "No response available.";
        })
        .catch(error => {
            console.error("Error:", error);
            chatResponse.textContent = "Error fetching response.";
        });
    });

    // Enter key triggers send
    document.getElementById("userInput").addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            document.getElementById("sendBtn").click();
        }
    });
</script>

</body>
</html>