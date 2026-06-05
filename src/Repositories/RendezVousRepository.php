<?php

require_once __DIR__ . '/../../config/database.php';

use Config\Database;

class RendezVousRepository
{
    public function trouverRendezVousActifs($id_user_medecin)
    {
        $pdo = Database::getConnection();

        // Rj3na tables o columns b l-Anglais kif 3ndek f database
        $sql = "SELECT a.id, a.status AS statut,
                       CONCAT(u_pat.lastname, ' ', u_pat.firstname) AS nom_patient,
                       TIME_FORMAT(t.start_time, '%H:%i') AS heure,
                       DATE(t.start_time) AS date_rdv,
                       DAYNAME(t.start_time) AS jour_semaine
                FROM appointments a
                JOIN users u_pat ON a.id_patient = u_pat.id
                JOIN timeslots t ON a.id_timeslot = t.id
                WHERE a.id_doctor = :id_user
                  AND a.status IN ('pending', 'confirmed')  
                ORDER BY t.start_time ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_user' => $id_user_medecin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function trouverRendezVousPasses($id_user_medecin)
    {
        $pdo = Database::getConnection();
        
        $sql = "SELECT a.id, a.status AS statut,
                       CONCAT(u_pat.lastname, ' ', u_pat.firstname) AS nom_patient,
                       TIME_FORMAT(t.start_time, '%H:%i') AS heure,
                       DATE(t.start_time) AS date_rdv,
                       DAYNAME(t.start_time) AS jour_semaine
                FROM appointments a
                JOIN users u_pat ON a.id_patient = u_pat.id
                JOIN timeslots t ON a.id_timeslot = t.id
                WHERE a.id_doctor = :id_user
                  AND a.status IN ('terminate', 'cancelled') 
                ORDER BY t.start_time DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_user' => $id_user_medecin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function modifierStatut($id_rdv, $nouveau_statut)
    {
        $pdo = Database::getConnection();
        
        // table hia appointments o column hoa status
        $sql = "UPDATE appointments SET status = :statut WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['statut' => $nouveau_statut, 'id' => $id_rdv]);
    }
}