<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'MedFlow' ?></title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col min-h-full font-sans antialiased text-slate-800">

<!-- HEADER GLOBAL -->
<header class="bg-white border-b border-slate-200/80 sticky top-0 z-40 backdrop-blur-md bg-white/90">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex justify-between items-center h-20">

            <!-- Logo Minimaliste -->
            <div class="flex items-center space-x-3">
                <div class="h-9 w-9 bg-emerald-600 rounded-xl flex items-center justify-center text-white font-black text-lg shadow-sm shadow-emerald-500/20">
                    M
                </div>
                <span class="text-xl font-bold tracking-tight text-slate-900">Med<span class="text-emerald-600 font-light">flow</span></span>
            </div>

            <!-- Liens de Navigation (Dynamiques selon le rôle si besoin) -->
            <nav class="hidden md:flex space-x-8 text-sm font-medium tracking-wide">
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'doctor'): ?>
                    <a href="/doctor/agenda" class="text-emerald-600 font-semibold">Planning</a>
                    <a href="#" class="text-slate-500 hover:text-slate-900 transition">Patientèle</a>
                <?php else: ?>
                    <a href="/patient/dashboard" class="text-emerald-600 font-semibold">Mes Rendez-vous</a>
                    <a href="#" class="text-slate-500 hover:text-slate-900 transition">Mon Dossier</a>
                <?php endif; ?>
            </nav>

            <!-- Profil Utilisateur -->
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-3 border-l border-slate-200 pl-4">
                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-700 border border-slate-200">
                        U
                    </div>
                    <div class="hidden lg:block text-left">
                        <p class="text-sm font-bold text-slate-800">Mon Compte</p>
                    </div>
                </div>
                <form action="/MedFlow-Gestion-de-Clinique-Medicale/public/index.php?action=logout" method="POST" class="inline m-0 p-0">
                    <button type="submit" class="text-slate-400 hover:text-rose-600 p-2 rounded-lg hover:bg-rose-50 transition" title="Déconnexion">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l4-4m-4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 01-3-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </button>
                </form>
            </div>

        </div>
    </div>
</header>
