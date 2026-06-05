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

// Hna khass l-Controller y-koun kay-recuperer kolchi (Pending, Confirmed, Termine, Annule)
$historique_liste = $controller->afficherHistorique();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Gestion des Rendez-vous</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        clinicGreen: '#9CC943',
                        clinicGreenHover: '#88b236',
                        clinicPrimary: '#1e293b',
                        medicalIceBg: '#eef2f7',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-medicalIceBg text-slate-700 font-sans min-h-screen">

    <div class="flex h-screen overflow-hidden">

        <aside class="w-64 bg-white border-r border-slate-200 p-6 flex flex-col justify-between hidden md:flex shadow-sm">
            <div>
                <div class="flex items-center gap-3 mb-10">
                    <div class="h-9 w-9 rounded-xl bg-clinicGreen flex items-center justify-center text-white font-black text-xl shadow-[0_4px_12px_rgba(156,201,67,0.3)]">M</div>
                    <span class="text-xl font-bold tracking-wider text-clinicPrimary">Med<span class="text-clinicGreen">Flow</span></span>
                </div>

                <nav class="space-y-1">
                    <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-900 text-sm font-medium transition">
                        Dashboard
                    </a>
                    <a href="ordonnance.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-900 text-sm font-medium transition">
                        Ordonnances
                    </a>
                    <a href="RendezVous.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-clinicGreen/10 text-clinicGreen font-semibold text-sm border-l-4 border-clinicGreen">
                        <span>🗓️ Gestion des Rendez-vous</span>
                    </a>
                </nav>
            </div>

            <div class="pt-4 border-t border-slate-100 text-xs text-slate-400 font-semibold truncate">
                Dr. <?= htmlspecialchars($_SESSION['nom'] ?? 'Médecin') ?>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            <header class="mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-clinicPrimary tracking-tight">Suivi des Consultations du Jour 📂</h1>
                <p class="text-sm text-slate-500 mt-1">Gérez le statut des rendez-vous et rédigez les ordonnances en temps réel.</p>
            </header>

            <section class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-base font-bold text-clinicPrimary tracking-wide">Liste des Rendez-vous</h2>
                </div>

                <div class="divide-y divide-slate-100">
                    <?php if (empty($historique_liste)): ?>
                        <div class="p-12 text-center text-slate-400 text-sm font-medium">
                            Aucun rendez-vous trouvé pour le moment.
                        </div>
                    <?php else: ?>
                        <?php foreach ($historique_liste as $rdv): 
                            $statut = strtolower($rdv['statut']);
                        ?>
                            <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/60 transition">

                                <div class="flex items-center gap-4">
                                    <div class="bg-slate-100 text-slate-600 font-mono text-xs px-3 py-2 rounded-xl border border-slate-200 text-center min-w-[75px]">
                                        <span class="block font-bold text-slate-700 uppercase"><?= htmlspecialchars($rdv['jour_semaine']) ?></span>
                                        <span class="text-[10px] text-slate-500"><?= htmlspecialchars($rdv['date_rdv']) ?></span>
                                    </div>
                                    <div class="font-mono text-sm px-3 py-2 rounded-xl border bg-slate-50 text-slate-600 border-slate-200">
                                        <?= htmlspecialchars($rdv['heure']) ?>
                                    </div>
                                    <div>
                                        <h4 class="text-base font-bold text-clinicPrimary"><?= htmlspecialchars($rdv['nom_patient']) ?></h4>
                                        <p class="text-xs text-slate-400 font-mono">ID: #RDV-<?= $rdv['id'] ?></p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <?php if ($statut === 'pending'): ?>
                                        <a href="modifier_statut.php?id_rdv=<?= $rdv['id'] ?>&status=confirmed" 
                                           class="px-4 py-2 text-xs font-bold rounded-xl text-white bg-emerald-500 hover:bg-emerald-600 shadow-sm transition">
                                            ✅ Confirmer l'arrivée
                                        </a>

                                    <?php elseif ($statut === 'confirmed'): ?>
                                        <a href="ordonnance.php?id_rdv=<?= $rdv['id'] ?>" 
                                           class="px-4 py-2 text-xs font-bold rounded-xl text-white bg-blue-500 hover:bg-blue-600 shadow-sm transition">
                                            📝 Rédiger une ordonnance
                                        </a>

                                    <?php elseif ($statut === 'terminate'): ?>
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200">
                                            ● Consultation Clôturée
                                        </span>
                                        <a href="details_ordo.php?id_rdv=<?= $rdv['id'] ?>" 
                                           class="px-3 py-1 text-xs font-bold rounded-full bg-clinicGreen/10 text-clinicGreen border border-clinicGreen/30 hover:bg-clinicGreen hover:text-white transition">
                                            Voir L'ordonnance
                                        </a>

                                    <?php else: ?>
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-rose-50 text-rose-600 border border-rose-200">
                                            ● Rendez-vous Annulé
                                        </span>
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