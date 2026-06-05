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
    public function ajouterTimeslot() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_doctor = $_SESSION['user_id'] ?? null;
            $date_slot = $_POST['date_slot'] ?? '';
            $heure_debut = $_POST['heure_debut'] ?? '';
            $heure_fin = $_POST['heure_fin'] ?? '';

            if (!$id_doctor || empty($date_slot) || empty($heure_debut) || empty($heure_fin)) {
                return "Tous les champs sont obligatoires.";
            }

            $start_time = $date_slot . ' ' . $heure_debut . ':00';
            $end_time = $date_slot . ' ' . $heure_fin . ':00';

            try {
                $pdo = Database::getConnection();
                
                // Query configuration 3la 7sab smiyt l-columns f table timeslots
                // Hna ftrdna smiyathom: start_time, end_time, id_doctor, status (disponible par défaut)
                $sql = "INSERT INTO timeslots (start_time, end_time, id_doctor, status) VALUES (?, ?, ?, 'disponible')";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$start_time, $end_time, $id_doctor]);

                header('Location: disponibilite.php?success=1');
                exit();
            } catch (Exception $e) {
                return "Erreur lors de l'ajout : " . $e->getMessage();
            }
        }
        return null;
    }
}

$controller = new MedecinController();
if (isset($_GET['action']) && isset($_GET['id'])) {
    $controller->gererStatut($_GET['action'], $_GET['id']);
}