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
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = Database::getConnection();
    $description = trim($_POST['description']);
    $id_rdv_post = intval($_POST['id_rendez_vous']);

    if (!empty($description) && $id_rdv_post > 0) {
        try {
            // checking f table prescriptions
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

            // Statut mbdl l 'terminate' b l-Anglais 
            $stmt2 = $pdo->prepare("UPDATE appointments SET status = 'terminate' WHERE id = ?");
            $stmt2->execute([$id_rdv_post]);

            $pdo->commit();
            header('Location: dashboard.php');
            exit();

        } catch (Exception $e) {
            $pdo->rollBack();
            $message = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    } else {
        $message = "Veuillez rédiger la description de l'ordonnance.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Rédiger Ordonnance</title>
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
<body class="bg-medicalIceBg text-slate-700 font-sans min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-2xl bg-white border border-slate-200/80 shadow-[0_10px_30px_rgba(30,41,59,0.05)] rounded-3xl p-8">
        <div class="mb-6">
            <span class="text-xs font-bold text-clinicGreen uppercase tracking-widest">Étape Finale</span>
            <h1 class="text-2xl font-black text-clinicPrimary mt-1">Rédiger l'ordonnance médicale 🩺</h1>
            <p class="text-xs text-slate-400 mt-1">Le statut du rendez-vous <span class="font-mono font-bold text-slate-600">#RDV-<?= $id_rdv ?></span> passera automatiquement à 'Terminé'.</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="bg-rose-50 border border-rose-200 text-rose-600 text-xs p-4 rounded-xl mb-6 text-center font-medium">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-6">
            <input type="hidden" name="id_rendez_vous" value="<?= $id_rdv ?>">

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-3">Détails du traitement (Médicaments, Posologie...)</label>
                <textarea name="description" rows="8" required placeholder="Ex: Paracétamol 500mg : 1 comprimé 3 fois par jour..." 
                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-clinicGreen focus:ring-2 focus:ring-clinicGreen/20 transition text-sm font-mono leading-relaxed shadow-inner"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="dashboard.php" class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl border border-slate-200 transition">Annuler</a>
                <button type="submit" class="px-5 py-3 bg-clinicGreen hover:bg-clinicGreenHover text-white font-bold text-xs rounded-xl shadow-md transition">Sauvegarder o Clôturer</button>
            </div>
        </form>
    </div>
</body>
</html>