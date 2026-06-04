<?php

namespace Repositories;

use Config\Database;
use PDO;
use PDOException;

class TimeslotRepository
{
    private PDO $pdo;

    public function __construct(){
        $this->pdo = Database::getConnection();
    }

    public function getAllTimeslots(){

    }
    public function getAvailableTimeslotsByDoctor($doctorId): array {
        try {
            $sql = "SELECT id, id_doctor, start_time, end_time 
                    FROM timeslots 
                    WHERE id_doctor = ? AND is_available = 1
                    ORDER BY start_time ASC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$doctorId]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching timeslots: " . $e->getMessage());
            return [];
        }
    }

    public function getTimeSlotsBySpeciality($specialityId): array {
        try {
            $sql = "SELECT ts.*, u.firstname, u.lastname 
                    FROM timeslots ts
                    JOIN doctors d ON ts.id_doctor = d.id
                    JOIN users u ON d.id_user = u.id
                    WHERE d.id_speciality = ?
                    AND ts.is_available = 1 
                    ORDER BY ts.start_time ASC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $specialityId
            ]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error in getTimeSlotsBySpeciality: " . $e->getMessage());
            return [];
        }
    }
}