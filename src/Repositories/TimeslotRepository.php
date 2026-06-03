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

    public function getAvailableTimeslotsByDoctor($doctorId){
        try {
            $sql = "SELECT id, start_time, end_time 
                FROM timeslots 
                WHERE id_doctor = ? AND is_available = 1 AND start_time >= NOW()
                ORDER BY start_time ASC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$doctorId]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching timeslots: " . $e->getMessage());
            return [];
        }
    }
}