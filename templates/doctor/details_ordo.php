<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
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
    <header class="w-full bg-white border-b border-slate-100 h-20 sticky top-0 z-50 no-print">
        <div class="max-w-7xl mx-auto px-4 h-full flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-clinicBlack flex items-center justify-center text-white font-extrabold text-lg">M</div>
                <span class="text-xl font-bold text-clinicBlack">Med<span class="text-clinicGreen">Flow</span></span>
            </div>
            <nav class="hidden md:flex items-center gap-1">
                <a href="dashboard.php" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-slate-400 hover:text-clinicBlack text-sm font-semibold transition">Dashboard</a>
                <a href="RendezVous.php" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-slate-400 hover:text-clinicBlack text-sm font-semibold transition">Rendez-vous</a>
            </nav>
            <div class="text-xs font-bold text-clinicBlack">Dr. <?= htmlspecialchars($_SESSION['nom'] ?? 'Médecin') ?></div>
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