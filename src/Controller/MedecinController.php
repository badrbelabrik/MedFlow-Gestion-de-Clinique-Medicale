<?php
namespace Controller;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../src/Repositories/RendezVousRepository.php';
require_once __DIR__ . '/../../src/Repositories/DoctorRepository.php';
require_once __DIR__ . '/../../config/database.php';

use Config\Database;
use Exception;
use RendezVousRepository;
use Repositories\DoctorRepository;

class MedecinController {

    public function afficherDashboard() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'doctor') {
            header('Location: ../../templates/auth/login.php');
            exit();
        }
        $repo = new RendezVousRepository();
        return $repo->trouverRendezVousActifs($_SESSION['user_id']);
    }

    public function afficherHistorique() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'doctor') {
            header('Location: ../../templates/auth/login.php');
            exit();
        }
        $repo = new RendezVousRepository();
        return $repo->trouverRendezVousPasses($_SESSION['user_id']);
    }

    public function gererStatut($action, $id_rdv) {
        $repo = new RendezVousRepository();

        if ($action === 'confirmer') {

            $repo->modifierStatut($id_rdv, 'confirmed');
        } 
        elseif ($action === 'annuler') {

            $repo->modifierStatut($id_rdv, 'cancelled');

            $pdo = Database::getConnection();

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
        $doctorRepo = new DoctorRepository();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_doctor = $doctorRepo->getDoctorByUserId($_SESSION['user_id'])['doctor_id'];
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
                

                $sql = "INSERT INTO timeslots (start_time, end_time, id_doctor) VALUES (?, ?, ?)";
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