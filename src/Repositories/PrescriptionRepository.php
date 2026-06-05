<?php

namespace Repositories;

use config\Database;
use PDO;
use PDOException;

class PrescriptionRepository
{
    private PDO $pdo;
    public function __construct(){
        $this->pdo = Database::getConnection();
    }

    public function getPrescriptionByAppointment(int $appointmentId, int $patientId): ?array {
        try {
            $sql = "SELECT p.description
                FROM prescriptions p
JOIN appointments a ON a.id = p.id_appointment
                WHERE a.id = ? 
                  AND a.id_patient = ?
                  AND a.status = 'terminate'";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$appointmentId, $patientId]);

            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $result ?: null;

        } catch (\PDOException $e) {
            error_log("Error in getPrescriptionByAppointment: " . $e->getMessage());
            return null;
        }
    }
}