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

<body class="bg-cover bg-center bg-no-repeat h-screen flex items-center justify-center"
      style="background-image: url('medical-treatment-calendar-with-stethoscope-pills (1).jpg');">


<div class="absolute inset-0 bg-black bg-opacity-40"></div>


<div class="relative bg-white p-8 rounded-2xl shadow-lg text-center">

    <h1 class="text-3xl font-bold text-blue-600 mb-4">
        MedFlow Dashboard
    </h1>

    <p class="text-lg text-gray-700">
        <?= $result ?>
    </p>

    <a href="login.php"
       class="inline-block mt-5 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        Back to Login
    </a>

</div>

</body>
</html>