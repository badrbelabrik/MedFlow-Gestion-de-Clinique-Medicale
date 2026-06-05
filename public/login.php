
<!DOCTYPE html>
<html>
<head>
    <title>MedFlow Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-screen flex">

<!-- LEFT SIDE -->
<div class="hidden md:flex w-1/2 bg-gradient-to-br from-green-700 via-green-600 to-emerald-500 text-white items-center justify-center p-10">

    <div class="text-center max-w-md">
        <div class="text-6xl mb-6">🏥</div>

        <h1 class="text-5xl font-bold mb-4">MedFlow</h1>

        <p class="text-white/80 text-lg">
            Hospital Management System
        </p>
    </div>

</div>

<!-- RIGHT SIDE -->
<div class="w-full md:w-1/2 flex items-center justify-center bg-gray-50">

    <div class="w-full max-w-md bg-white shadow-2xl rounded-3xl p-10 border-t-4 border-green-500">

        <h1 class="text-3xl font-bold text-center text-green-600 mb-6">
            Welcome Back
        </h1>

        <!-- ⚠️ IMPORTANT: keep your PHP variable EXACT -->
        <?php if (isset($result) && $result != ""): ?>
            <div class="bg-red-100 text-red-600 p-3 rounded-xl mb-4 text-center">
                <?= $result ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">

            <input type="email" name="email" placeholder="Email"
                   class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-green-500 outline-none">

            <input type="password" name="password" placeholder="Password"
                   class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-green-500 outline-none">

            <button class="w-full bg-green-600 hover:bg-green-700 text-white p-3 rounded-xl font-semibold transition">
                Sign In
            </button>

        </form>

    </div>

</div>

</body>
</html>