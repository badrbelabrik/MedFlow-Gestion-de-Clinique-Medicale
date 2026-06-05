<?php

require_once __DIR__ . '/../../config/database.php';

class StatsRepository {

    public function getGlobalStats(): array {

        $db = getDB();

        return [
            'total_medecins' => $db->query("SELECT COUNT(*) FROM doctors")->fetchColumn(),
            'total_patients' => $db->query("SELECT COUNT(*) FROM users WHERE role='patient'")->fetchColumn(),
            'total_rdv' => $db->query("SELECT COUNT(*) FROM appointments")->fetchColumn(),
            'rdv_termine' => $db->query("SELECT COUNT(*) FROM appointments WHERE status='terminated'")->fetchColumn(),
            'rdv_annule' => $db->query("SELECT COUNT(*) FROM appointments WHERE status='cancelled'")->fetchColumn(),
            'rdv_en_attente' => $db->query("SELECT COUNT(*) FROM appointments WHERE status='pending'")->fetchColumn(),
        ];
    }

    public function getRdvParMedecin(): array {

        return getDB()->query("
            SELECT
                CONCAT(u.firstname,' ',u.lastname) AS medecin,
                sp.name AS specialite,
                COUNT(a.id) AS total_rdv,
                SUM(a.status='terminated') AS termines,
                SUM(a.status='cancelled') AS annules,
                SUM(a.status='pending') AS en_attente
            FROM doctors d
            JOIN users u ON u.id = d.id_user
            JOIN specialities sp ON sp.id = d.id_speciality
            LEFT JOIN appointments a ON a.id_doctor = u.id
            GROUP BY d.id
            ORDER BY total_rdv DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTauxAnnulation(): float {

        $stats = $this->getGlobalStats();

        if ($stats['total_rdv'] == 0) {
            return 0;
        }

        return round(
            ($stats['rdv_annule'] / $stats['total_rdv']) * 100,
            2
        );
    }
}