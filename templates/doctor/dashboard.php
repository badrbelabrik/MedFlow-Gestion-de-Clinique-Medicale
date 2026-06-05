<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../src/Controller/MedecinController.php';
$controller = new MedecinController();
$rendez_vous_liste = $controller->afficherDashboard(); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Espace Médecin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        clinicGreen: '#9CC943',
                        clinicGreenHover: '#88b236',
                        clinicPrimary: '#0f172a', // نصوص غامقة واضحة
                        clinicBg: '#f8fafc', // خلفية بيضاء طبية نقية
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-clinicBg text-slate-700 font-sans min-h-screen">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-white border-r border-slate-200 p-6 flex flex-col justify-between hidden md:flex shadow-sm">
            <div>
                <div class="flex items-center gap-3 mb-10">
                    <div class="h-9 w-9 rounded-xl bg-clinicGreen flex items-center justify-center text-white font-black text-xl shadow-[0_4px_12px_rgba(156,201,67,0.3)]">M</div>
                    <span class="text-xl font-bold tracking-wider text-clinicPrimary">Med<span class="text-clinicGreen">Flow</span></span>
                </div>
                
                <nav class="space-y-1">
                    <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-clinicGreen/10 text-clinicGreen font-semibold text-sm border-l-4 border-clinicGreen">
                        Dashboard
                    </a>
                    <a href="ordonnance.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-900 text-sm font-medium transition">
                        Ordonnances
                    </a>
                    <a href="RendezVous.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-900 text-sm font-medium transition">
                        🗓️ Rendez-vous Passés
                    </a>
                </nav>
            </div>
            
            <div class="pt-4 border-t border-slate-100 text-xs text-slate-400 font-semibold truncate">
                Dr. <?= htmlspecialchars($_SESSION['nom'] ?? 'Médecin') ?>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            
            <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-clinicPrimary tracking-tight">Gestion des Consultations ⚡</h1>
                    <p class="text-sm text-slate-500 mt-1">Confirmez les demandes, puis rédigez l'ordonnance pour clore le RDV.</p>
                </div>
            </header>

            <section class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h2 class="text-base font-bold text-clinicPrimary tracking-wide">Flux des Consultations Actives</h2>
                    <span class="text-xs bg-slate-200/60 text-slate-600 font-semibold px-2.5 py-1 rounded-md">Aujourd'hui</span>
                </div>

                <div class="divide-y divide-slate-100">
                    <?php if (empty($rendez_vous_liste)): ?>
                        <div class="p-12 text-center text-slate-400 text-sm font-medium">
                            ✨ Aucun rendez-vous à traiter pour le moment.
                        </div>
                    <?php else: ?>
                        <?php foreach ($rendez_vous_liste as $rdv): 
                            $isConfirme = (strtolower($rdv['statut']) === 'confirmed');
                        ?>
                            <div class="p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4 hover:bg-slate-50/60 transition">
                                
                                <div class="flex items-center gap-4">
                                    <div class="bg-slate-100 text-slate-600 font-mono text-xs px-3 py-2 rounded-xl border border-slate-200 text-center min-w-[75px]">
                                        <span class="block font-bold text-slate-700 uppercase"><?= htmlspecialchars($rdv['jour_semaine']) ?></span>
                                        <span class="text-[10px] text-slate-500"><?= htmlspecialchars($rdv['date_rdv']) ?></span>
                                    </div>
                                    <div class="bg-blue-50 text-blue-600 font-mono font-bold text-sm px-3 py-2 rounded-xl border border-blue-100 shadow-sm">
                                        <?= htmlspecialchars($rdv['heure']) ?>
                                    </div>
                                    <div>
                                        <h4 class="text-base font-bold text-clinicPrimary"><?= htmlspecialchars($rdv['nom_patient']) ?></h4>
                                        <span class="text-xs text-slate-400 font-mono">ID: #RDV-<?= $rdv['id'] ?></span>
                                    </div>
                                </div>

                                <div>
                                    <?php if ($isConfirme): ?>
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-50 text-blue-600 border border-blue-200">
                                            ● Confirmé
                                        </span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-amber-50 text-amber-600 border border-amber-200">
                                            ● En attente
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="flex items-center gap-2">
                                    <?php if (!$isConfirme): ?>
                                        <a href="../../src/Controller/MedecinController.php?action=confirmer&id=<?= $rdv['id'] ?>" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl transition shadow-sm">
                                            Confirmer
                                        </a>
                                        <a href="../../src/Controller/MedecinController.php?action=annuler&id=<?= $rdv['id'] ?>" class="px-3 py-2 bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-500 font-medium text-xs rounded-xl border border-slate-200 transition">
                                            Annuler
                                        </a>
                                    <?php else: ?>
                                        <a href="ordonnance.php?id_rdv=<?= $rdv['id'] ?>" class="px-4 py-2 bg-clinicGreen hover:bg-clinicGreenHover text-white font-black text-xs rounded-xl shadow-[0_3px_10px_rgba(156,201,67,0.3)] transition tracking-wider uppercase">
                                            Rédiger Ordonnance
                                        </a>
                                    <?php endif; ?>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>

</body>
</html>