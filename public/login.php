<!DOCTYPE html>
<html>
<head>
    <title>MedFlow - Medical Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-screen flex items-center justify-center bg-cover bg-center relative"
      style="background-image: url('medical-treatment-calendar-with-stethoscope-pills (1).jpg');">


<div class="absolute inset-0 bg-slate-900/60"></div>

<div class="relative w-96 bg-white/95 backdrop-blur-md p-8 rounded-2xl shadow-2xl">

  
    <div class="flex justify-center mb-4">
        <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
            🏥
        </div>
    </div>

    <!-- title -->
    <h1 class="text-3xl font-bold text-center text-blue-700">
        MedFlow
    </h1>

    <p class="text-center text-gray-500 mb-6">
        Hospital Management Login
    </p>

  
    <form method="POST" action="index.php" class="space-y-4">

        <input type="email" name="email" placeholder="Doctor / Patient Email"
               class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">

        <input type="password" name="password" placeholder="Password"
               class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">

        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg font-semibold transition">
            Sign In
        </button>

    </form>

  
    <p class="text-xs text-center text-gray-400 mt-4">
        Secure access for medical staff & patients
    </p>

</div>

</body>
</html>