<?php
require_once __DIR__ . "/../src/Controller/AuthController.php";

$auth = new AuthController();

$result = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $result = $auth->login($_POST["email"], $_POST["password"]);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>MedFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-screen flex items-center justify-center bg-cover bg-center relative"
      style="background-image: url('medical-treatment-calendar-with-stethoscope-pills (1).jpg');">

<!-- overlay -->
<div class="absolute inset-0 bg-gradient-to-br from-blue-900/60 via-black/50 to-green-900/40"></div>

<!-- card -->
<div class="relative w-[90%] max-w-md">

    <div class="backdrop-blur-xl bg-white/10 border border-white/20 shadow-2xl rounded-3xl p-10 text-center text-white">

        <!-- logo / title -->
        <div class="mb-6">
            <div class="w-16 h-16 mx-auto bg-white/20 rounded-full flex items-center justify-center mb-3">
                🏥
            </div>

            <h1 class="text-2xl font-bold text-green-600 mb-4">
    You are logged out
</h1>
            <p class="text-sm text-white/70 mt-2">
                Hospital Management System
            </p>
        </div>

        <!-- result -->
        <div class="bg-white/10 border border-white/20 rounded-xl p-4 mb-6">
            <p class="text-lg text-white">
                <?= $result ?>
            </p>
        </div>

        <!-- button -->
        <a href="login.php"
           class="inline-block w-full bg-green-600 hover:bg-green-600 transition-all duration-300 text-white font-semibold py-3 rounded-xl shadow-lg hover:scale-105">

            Back to Login
        </a>

    </div>

</div>

</body>
</html>