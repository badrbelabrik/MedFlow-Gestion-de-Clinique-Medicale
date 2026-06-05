<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../src/Repositories/RendezVousRepository.php';
require_once __DIR__ . '/../../config/database.php';

// Importi l-Database namespace bash nkhdmou b getConnection()
use Config\Database;

class MedecinController {

    public function afficherDashboard() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
            header('Location: ../../templates/auth/login.php');
            exit();
        }
        $repo = new RendezVousRepository();
        return $repo->trouverRendezVousActifs($_SESSION['user_id']);
    }

    public function afficherHistorique() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
            header('Location: ../../templates/auth/login.php');
            exit();
        }
        $repo = new RendezVousRepository();
        return $repo->trouverRendezVousPasses($_SESSION['user_id']);
    }

    public function gererStatut($action, $id_rdv) {
        $repo = new RendezVousRepository();

        if ($action === 'confirmer') {
            // FIX 1: Rednaha 'confirmed' b l-Anglais
            $repo->modifierStatut($id_rdv, 'confirmed');
        } 
        elseif ($action === 'annuler') {
            // FIX 2: Rednaha 'cancelled' b l-Anglais
            $repo->modifierStatut($id_rdv, 'cancelled');

            // FIX 3: n7ni l-global o njibo l-connection s7i7a
            $pdo = Database::getConnection();

            // FIX 4: Query m9adda b l-Anglais 100% 3la ksaft tables dyalk
            $sql = "UPDATE timeslots t
                    JOIN appointments a ON a.id_timeslot = t.id 
                    SET t.is_available = 1 
                    WHERE a.id = ?";
                    
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id_rdv]);
        }

        header('Location: ../../templates/doctor/dashboard.php');
        exit();
    }
}

$controller = new MedecinController();
if (isset($_GET['action']) && isset($_GET['id'])) {
    $controller->gererStatut($_GET['action'], $_GET['id']);
}