<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'doctor') {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../config/database.php';
use Config\Database;

$id_rdv = isset($_GET['id_rdv']) ? intval($_GET['id_rdv']) : 0;
$ordonnance = null;

if ($id_rdv > 0) {
    $pdo = Database::getConnection();
    
    $sql = "SELECT o.description, o.id AS id_ordonnance,
                   CONCAT(u_pat.lastname, ' ', u_pat.firstname) AS nom_patient,
                   t.start_time AS date_consultation
            FROM prescriptions o
            JOIN appointments r ON o.id_appointment = r.id
            JOIN users u_pat ON r.id_patient = u_pat.id
            JOIN timeslots t ON r.id_timeslot = t.id
            WHERE r.id = ?";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_rdv]);
    $ordonnance = $stmt->fetch(PDO::FETCH_ASSOC);
}
require_once __DIR__ . '/../../config/bootstrap.php';

Middleware\AuthMiddleware::checkRoles(['doctor']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Détails de l'ordonnance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            body { background: white !important; color: black !important; }
            .no-print { display: none !important; }
            .print-card { border: none !important; box-shadow: none !important; padding: 0 !important; max-w: 100% !important; }
            .prescription-box { border: 1px solid #000 !important; background: transparent !important; }
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        clinicBlack: '#0f172a',
                        clinicGreen: '#10b981',
                        clinicBg: '#f8fafc',
                    },
                    boxShadow: { 'premium': '0 20px 40px -15px rgba(15, 23, 42, 0.05)' }
                }
            }
        }
    </script>
</head>
<body class="bg-clinicBg text-slate-600 min-h-screen antialiased flex flex-col">

    <!-- HEADER (no-print) -->
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

    <!-- CONTAINER -->
    <div class="flex-1 flex items-center justify-center p-4 sm:p-8">
        <div class="w-full max-w-2xl bg-white border border-slate-100 rounded-3xl p-6 md:p-10 shadow-premium print-card">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 border-b border-slate-50 pb-6">
                <div>
                    <h1 class="text-2xl font-extrabold text-clinicBlack tracking-tight">Ordonnance Médicale</h1>
                </div>
                <div class="text-left sm:text-right font-mono text-xs text-slate-400">
                    <p class="font-semibold text-slate-700">REF: #RDV-<?= $id_rdv ?></p>
                    <?php if ($ordonnance): ?>
                        <p>N° ORD: #ORD-<?= $ordonnance['id_ordonnance'] ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!$ordonnance): ?>
                <div class="text-center py-16 no-print">
                    <h3 class="text-base font-bold text-clinicBlack">Aucune ordonnance trouvée</h3>
                    <a href="RendezVous.php" class="mt-6 inline-block text-xs bg-slate-100 text-slate-600 font-bold px-4 py-2 rounded-xl">Retour</a>
                </div>
            <?php else: ?>
                <div class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-5 rounded-2xl border border-slate-100">
                        <div>
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Patient</span>
                            <span class="text-base font-bold text-clinicBlack"><?= htmlspecialchars($ordonnance['nom_patient']) ?></span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Consultation du</span>
                            <span class="text-sm font-mono font-bold text-slate-700"><?= htmlspecialchars($ordonnance['date_consultation']) ?></span>
                        </div>
                    </div>

                    <div>
                        <div class="prescription-box bg-slate-50/30 border-2 border-dashed border-slate-200 rounded-2xl p-6 text-slate-800 font-mono text-sm leading-relaxed whitespace-pre-wrap min-h-[220px]">
                            <?= htmlspecialchars($ordonnance['description']) ?>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-slate-50 no-print">
                        <button onclick="window.print()" class="w-full sm:w-auto px-5 py-3 bg-clinicBlack hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition flex items-center justify-center gap-2">Imprimer</button>
                        <a href="RendezVous.php" class="w-full sm:w-auto text-center px-6 py-3 bg-clinicGreen hover:bg-clinicGreenHover text-white font-extrabold text-xs rounded-xl transition uppercase tracking-wider">Retour</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>