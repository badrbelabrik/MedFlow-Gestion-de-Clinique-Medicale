<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Logout</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-screen flex items-center justify-center bg-center bg-no-repeat bg-cover"
      style="background-image: url('elevated-view-medical-equipment-pills-with-open-laptop.jpg');">

<div class="bg-white p-8 rounded-2xl shadow-lg text-center w-96">

    <h1 class="text-2xl font-bold text-red-500 mb-4">
        You are logged out
    </h1>

    <p class="text-gray-600 mb-6">
        Redirecting to login...
    </p>

    <a href="login.php"
       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        Go to Login
    </a>

</div>

</body>
</html>