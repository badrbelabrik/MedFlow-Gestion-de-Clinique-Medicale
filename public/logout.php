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
    <title>Logout - MedFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- auto redirect after 5 seconds -->
    <meta http-equiv="refresh" content="5;url=login.php">
</head>

<body class="h-screen flex">

<!-- LEFT SIDE (same green branding as login) -->
<div class="hidden md:flex w-1/2 bg-gradient-to-br from-green-700 via-green-600 to-emerald-500 text-white items-center justify-center p-10">

    <div class="text-center max-w-md">

        <div class="text-6xl mb-6">🏥</div>

        <h1 class="text-5xl font-bold mb-4">
            MedFlow
        </h1>

        <p class="text-white/80 text-lg">
            Thank you for using our hospital system
        </p>

        <div class="mt-6 text-white/60 text-sm">
            Stay healthy ❤️
        </div>

    </div>
</div>

<!-- RIGHT SIDE -->
<div class="w-full md:w-1/2 flex items-center justify-center bg-gray-50">

    <div class="w-full max-w-md bg-white shadow-2xl rounded-3xl p-10 border-t-4 border-green-500 text-center">

        <!-- icon -->
        <div class="text-5xl mb-4">👋</div>

        <!-- title -->
        <h1 class="text-2xl font-bold text-gray-800 mb-2">
            You are logged out
        </h1>

        <p class="text-gray-500 mb-6">
            Your session has been closed securely.
        </p>

        <!-- info box -->
        <div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded-xl text-sm mb-6">
            Redirecting to login page...
        </div>

        <!-- button -->
        <a href="login.php"
           class="block w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-semibold transition">
            Go to Login
        </a>

    </div>

</div>

</body>
</html>