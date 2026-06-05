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
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = Database::getConnection();
    $description = trim($_POST['description']);
    $id_rdv_post = intval($_POST['id_rendez_vous']);

    if (!empty($description) && $id_rdv_post > 0) {
        try {
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM prescriptions WHERE id_appointment = ?");
            $checkStmt->execute([$id_rdv_post]);
            $deja_existe = $checkStmt->fetchColumn();

            $pdo->beginTransaction();

            if ($deja_existe == 0) {
                $stmt1 = $pdo->prepare("INSERT INTO prescriptions (description, id_appointment) VALUES (?, ?)");
                $stmt1->execute([$description, $id_rdv_post]);
            } else {
                $stmt1 = $pdo->prepare("UPDATE prescriptions SET description = ? WHERE id_appointment = ?");
                $stmt1->execute([$description, $id_rdv_post]);
            }

            $stmt2 = $pdo->prepare("UPDATE appointments SET status = 'terminate' WHERE id = ?");
            $stmt2->execute([$id_rdv_post]);

            $pdo->commit();
            header('Location: dashboard.php');
            exit();

        } catch (Exception $e) {
            $pdo->rollBack();
            $message = "Erreur : " . $e->getMessage();
        }
    } else {
        $message = "Veuillez rédiger la description de l'ordonnance.";
    }
}

require_once __DIR__ . '/../../config/bootstrap.php';

Middleware\AuthMiddleware::checkRoles(['doctor']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Rédiger Ordonnance</title>
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
                        'premium': '0 20px 40px -15px rgba(15, 23, 42, 0.05)',
                        'glow-green': '0 4px 25px -2px rgba(16, 185, 129, 0.2)',
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

    <!-- WRAPPER -->
    <div class="flex-1 flex items-center justify-center p-4 sm:p-8">
        <div class="w-full max-w-2xl bg-white border border-slate-100 rounded-3xl p-6 md:p-10 shadow-premium">
            
            <div class="mb-8 border-b border-slate-50 pb-5">
                <span class="text-[10px] font-bold text-clinicGreen uppercase tracking-widest bg-clinicGreen/10 px-3 py-1.5 rounded-xl border border-clinicGreen/20">Clôture Médicale</span>
                <h1 class="text-2xl font-extrabold text-clinicBlack tracking-tight mt-3">Rédiger l'ordonnance</h1>
                <p class="text-xs text-slate-400 mt-1 font-medium">Saisie sécurisée pour la fiche patient <span class="font-mono font-bold text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded">#RDV-<?= $id_rdv ?></span>.</p>
            </div>

            <?php if (!empty($message)): ?>
                <div class="bg-rose-50 text-rose-600 text-xs p-4 rounded-xl mb-6 text-center font-semibold"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-6">
                <input type="hidden" name="id_rendez_vous" value="<?= $id_rdv ?>">

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Traitements & Observations</label>
                    <textarea name="description" rows="9" required placeholder="Médicaments, posologie, et durée du traitement..." 
                        class="w-full bg-slate-50/50 border-2 border-slate-100 rounded-2xl p-4 md:p-5 text-slate-800 focus:outline-none focus:border-clinicGreen focus:bg-white focus:ring-4 focus:ring-clinicGreen/10 transition duration-200 text-sm font-mono leading-relaxed shadow-inner"></textarea>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-2">
                    <a href="dashboard.php" class="w-full sm:w-auto text-center px-5 py-3 bg-slate-50 text-slate-500 font-bold text-xs rounded-xl border border-slate-200/60 transition">Annuler</a>
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-clinicGreen hover:bg-clinicGreenHover text-white font-extrabold text-xs rounded-xl shadow-glow-green transition uppercase tracking-wider">Enregistrer & Fermer</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>