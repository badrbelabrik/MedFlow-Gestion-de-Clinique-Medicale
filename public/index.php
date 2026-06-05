<?php
declare(strict_types=1);

spl_autoload_register(function ($class) {
    $classPath = str_replace('\\', '/', $class);

    if (str_starts_with($class, 'config\\') || str_starts_with($class, 'config/')) {
        $file = __DIR__ . '/../' . $classPath . '.php';
    } else {
        $file = __DIR__ . '/../src/' . $classPath . '.php';
    }

    if (file_exists($file)) {
        require_once $file;
    }
});

use Controller\AuthController;

$authController = new AuthController();
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $authController->login();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'logout') {
    $authController->logout();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_GET['action'])) {
    $result = $authController->login();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MedFlow Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-screen flex m-0 p-0">

<div class="hidden md:flex w-1/2 bg-gradient-to-br from-green-700 via-green-600 to-emerald-500 text-white items-center justify-center p-10">
    <div class="text-center max-w-md">
        <div class="text-6xl mb-6">🏥</div>
        <h1 class="text-5xl font-bold mb-4">MedFlow</h1>
        <p class="text-white/80 text-lg">Hospital Management System</p>
    </div>
</div>

<div class="w-full md:w-1/2 flex items-center justify-center bg-gray-50">
    <div class="w-full max-w-md bg-white shadow-2xl rounded-3xl p-10 border-t-4 border-green-500">
        <h1 class="text-3xl font-bold text-center text-green-600 mb-6">Welcome Back</h1>

        <?php if (!empty($result)): ?>
            <div class="bg-red-100 text-red-600 p-3 rounded-xl mb-4 text-center text-sm font-semibold">
                <?= htmlspecialchars($result) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <input type="email" name="email" placeholder="Email" required
                   class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-green-500 outline-none">

            <input type="password" name="password" placeholder="Password" required
                   class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-green-500 outline-none">

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white p-3 rounded-xl font-semibold transition">
                Sign In
            </button>
        </form>
    </div>
</div>

</body>
</html>
