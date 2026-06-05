<?php

use Controller\MedecinController;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'doctor') {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../src/Controller/MedecinController.php';
$controller = new MedecinController();
$rendez_vous_liste = $controller->afficherDashboard();
require_once __DIR__ . '/../../config/bootstrap.php';

Middleware\AuthMiddleware::checkRoles(['doctor']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Espace Médecin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        clinicBlack: '#0f172a',   
                        clinicGreen: '#10b981',
                        clinicGreenHover: '#059669',
                        clinicBg: '#f8fafc',       
                    },
                    boxShadow: {
                        'premium': '0 10px 30px -10px rgba(15, 23, 42, 0.04), 0 1px 3px rgba(15, 23, 42, 0.02)',
                        'glow-green': '0 4px 20px -2px rgba(16, 185, 129, 0.25)',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-clinicBg text-slate-600 min-h-screen antialiased flex flex-col">

    <!-- GLOBAL HEADER -->
    <header class="w-full bg-white border-b border-slate-100 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">

            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-clinicBlack flex items-center justify-center text-white font-extrabold text-lg">M</div>
                <span class="text-xl font-bold tracking-tight text-clinicBlack">Med<span class="text-clinicGreen">Flow</span></span>
            </div>

            <nav class="hidden md:flex items-center gap-1">
                <a href="dashboard.php" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-clinicGreen/10 text-clinicGreen font-bold text-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                    Dashboard
                </a>
                <a href="disponibilite.php" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-slate-400 hover:bg-slate-50 hover:text-clinicBlack text-sm font-semibold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Disponibilités
                </a>
                <a href="RendezVous.php" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-slate-400 hover:bg-slate-50 hover:text-clinicBlack text-sm font-semibold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Rendez-vous
                </a>
            </nav>

            <div class="flex items-center gap-4">

                <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-2xl border border-slate-100/80 max-w-[220px]">
                    <div class="h-8 w-8 rounded-lg bg-clinicBlack text-white flex items-center justify-center font-bold text-xs shrink-0">Dr</div>
                    <div class="truncate">
                        <p class="text-[10px] text-slate-400 font-medium leading-none">Médecin</p>
                        <p class="text-xs font-bold text-clinicBlack truncate mt-0.5">Dr. <?= htmlspecialchars($_SESSION['user_firstname'] ?? 'Médecin') ?></p>
                    </div>
                </div>

                <form action="/MedFlow-Gestion-de-Clinique-Medicale/public/index.php?action=logout" method="POST" class="inline m-0 p-0">
                    <button type="submit" class="text-slate-400 hover:text-rose-600 p-2.5 rounded-xl hover:bg-rose-50 transition border border-transparent hover:border-rose-100 flex items-center justify-center" title="Déconnexion">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l4-4m-4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 01-3-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </button>
                </form>

            </div>

        </div>
    </header>

    <!-- MAIN WRAPPER -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8">
        
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-extrabold text-clinicBlack tracking-tight">Suivi Hospitalier</h1>
            <p class="text-sm text-slate-400 mt-1 font-medium">Gestion en temps réel de la file d'attente médicale.</p>
        </div>

        <section class="bg-white rounded-3xl border border-slate-100 shadow-premium overflow-hidden">
            <div class="px-6 py-4 sm:px-8 border-b border-slate-50 bg-slate-50/30 flex justify-between items-center">
                <h2 class="text-sm font-bold text-clinicBlack uppercase tracking-wider">Salle d'attente active</h2>
                <span class="text-xs bg-clinicGreen/10 text-clinicGreen font-bold px-3 py-1.5 rounded-xl border border-clinicGreen/20">Flux en direct</span>
            </div>

            <div class="divide-y divide-slate-50">
                <?php if (empty($rendez_vous_liste)): ?>
                    <div class="p-16 text-center">
                        <p class="text-sm text-slate-400 font-medium">Aucun patient en attente.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($rendez_vous_liste as $rdv): 
                        $isConfirme = (strtolower($rdv['statut']) === 'confirmed');
                    ?>
                        <div class="p-6 sm:p-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6 hover:bg-slate-50/50 transition duration-200">
                            <div class="flex flex-wrap items-center gap-4 sm:gap-6">
                                <div class="bg-slate-50 text-slate-600 px-4 py-2.5 rounded-2xl border border-slate-100 text-center min-w-[90px]">
                                    <span class="block text-xs font-extrabold text-clinicBlack tracking-wide uppercase"><?= htmlspecialchars($rdv['jour_semaine']) ?></span>
                                    <span class="text-[11px] font-medium text-slate-400 block mt-0.5"><?= htmlspecialchars($rdv['date_rdv']) ?></span>
                                </div>
                                <div class="font-mono text-sm font-bold px-4 py-2.5 rounded-2xl bg-clinicBlack text-white">
                                    <?= htmlspecialchars($rdv['heure']) ?>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-400 font-semibold font-mono tracking-wider">#RDV-<?= $rdv['id'] ?></p>
                                    <h4 class="text-lg font-bold text-clinicBlack tracking-tight"><?= htmlspecialchars($rdv['nom_patient']) ?></h4>
                                </div>
                            </div>

                            <div class="flex items-center lg:justify-center">
                                <?php if ($isConfirme): ?>
                                    <span class="px-3 py-1.5 text-xs font-bold rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center gap-1.5 tracking-wide uppercase">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Admis / Confirmé
                                    </span>
                                <?php else: ?>
                                    <span class="px-3 py-1.5 text-xs font-bold rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center gap-1.5 tracking-wide uppercase">
                                        En attente
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="flex items-center gap-3 self-end lg:self-auto">
                                <?php if (!$isConfirme): ?>
                                    <a href="../../src/Controller/MedecinController.php?action=confirmer&id=<?= $rdv['id'] ?>" 
                                       class="px-5 py-2.5 bg-clinicGreen hover:bg-clinicGreenHover text-white font-bold text-xs rounded-xl transition duration-200 shadow-md flex items-center gap-1.5">
                                        Admettre
                                    </a>
                                    <a href="../../src/Controller/MedecinController.php?action=annuler&id=<?= $rdv['id'] ?>" 
                                       class="px-4 py-2.5 bg-slate-50 hover:bg-rose-50 hover:text-rose-600 text-slate-400 text-xs font-semibold rounded-xl border border-slate-200/60 transition duration-200">
                                        Annuler
                                    </a>
                                <?php else: ?>
                                    <a href="ordonnance.php?id_rdv=<?= $rdv['id'] ?>" 
                                       class="px-5 py-2.5 bg-clinicBlack hover:bg-slate-800 text-white font-extrabold text-xs rounded-xl transition duration-200 flex items-center gap-1.5 tracking-wider uppercase">
                                        <span>Consulter & Traiter</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

</body>
</html>