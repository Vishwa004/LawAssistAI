<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Law Assist AI - Contact Us</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100 flex flex-col min-h-screen">

  <?php 
    session_start(); 
    $successMessage = isset($_SESSION['success']) ? $_SESSION['success'] : "";
    $errorMessage = isset($_SESSION['error']) ? $_SESSION['error'] : "";
    unset($_SESSION['success'], $_SESSION['error']);  // Clear session messages after use
  ?>

  <nav class="bg-white shadow-md py-4 px-8 flex items-center">
    <div class="flex items-center space-x-2">
      <img src="https://i.imgur.com/UOLOnGk.png" alt="Law Assist AI Logo" class="w-8 h-8" />
      <h1 class="text-xl font-bold text-blue-600">Law A<span class="text-blue-400">ssist AI</span></h1>
    </div>
    <div class="flex-grow flex justify-center">
      <div class="hidden md:flex space-x-6 text-gray-600">
        <a href="../Frontend/home_page.php" class="text-gray-700 hover:text-black">Home</a>
        <a href="../Frontend/law_page.html" class="text-gray-700 hover:text-black">Laws</a>
        <a href="../Frontend/about.html" class="text-gray-700 hover:text-black">About Us</a>
        <a href="../Frontend/contact.php" class="text-gray-700 hover:text-black font-bold border-b-2 border-black">Contact Us</a>
      </div>
    </div>
  </nav>

  <!-- Popup Message -->
  <div id="popup-message" class="hidden fixed top-0 left-0 w-full h-full flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-md shadow-md text-center">
        <p id="popup-text" class="text-gray-800"></p>
        <button onclick="closePopup()" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">OK</button>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    function showPopup(message) {
        document.getElementById("popup-text").innerText = message;
        document.getElementById("popup-message").classList.remove("hidden");
    }

    function closePopup() {
        document.getElementById("popup-message").classList.add("hidden");
    }

    window.onload = function() {
        let successMessage = <?php echo json_encode($successMessage); ?>;
        let errorMessage = <?php echo json_encode($errorMessage); ?>;

        if (successMessage) {
            showPopup(successMessage);
        }
        if (errorMessage) {
            showPopup(errorMessage);
        }
    };
  </script>

  <!-- Main Content -->
  <div class="flex-grow container mx-auto p-6">
    <section class="bg-white rounded-lg shadow-md p-6 mb-6">
      <h2 class="text-2xl font-bold text-blue-700 mb-4">Get in Touch</h2>
      <p class="text-gray-700 leading-relaxed">Have questions or need assistance? Reach out to us!</p>
    </section>

    <section class="bg-white rounded-lg shadow-md p-6 mb-6">
      <h2 class="text-2xl font-bold text-blue-700 mb-4">Contact Us</h2>
      <form action="../Backend/get_contact.php" method="POST" class="space-y-4">
        <div>
          <label class="block text-gray-700">Name</label>
          <input type="text" name="name" class="w-full border-gray-300 rounded-md p-2" placeholder="Your Name" required />
        </div>
        <div>
          <label class="block text-gray-700">Email</label>
          <input type="email" name="email" class="w-full border-gray-300 rounded-md p-2" placeholder="Your Email" required />
        </div>
        <div>
          <label class="block text-gray-700">Subject</label>
          <input type="text" name="subject" class="w-full border-gray-300 rounded-md p-2" placeholder="Subject" required />
        </div>
        <div>
          <label class="block text-gray-700">Message</label>
          <textarea name="message" class="w-full border-gray-300 rounded-md p-2" rows="4" placeholder="Your Message" required></textarea>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Send Message</button>
      </form>
    </section>

    <section class="bg-white rounded-lg shadow-md p-6 mb-6">
      <h2 class="text-2xl font-bold text-blue-700 mb-4">Our Contact Information</h2>
      <p class="text-gray-700"><strong>Email:</strong> lawassistai@gmail.com</p>
      <p class="text-gray-700"><strong>Phone:</strong> +91 9876543210</p>
      <p class="text-gray-700"><strong>Address:</strong> 123 Legal Street, Law City, LC 45678</p>
    </section>
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

</body>
</html>
