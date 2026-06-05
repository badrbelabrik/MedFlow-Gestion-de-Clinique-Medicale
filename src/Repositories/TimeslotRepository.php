<?php

namespace Repositories;

use config\Database;
use PDO;
use PDOException;

class TimeslotRepository
{
    private PDO $pdo;

    public function __construct(){
        $this->pdo = Database::getConnection();
    }

    public function getAllAvailableTimeslots(): array {
        try {
            $sql = "SELECT 
                    ts.id AS id, 
                    ts.id_doctor AS id_doctor, 
                    ts.start_time AS start_time, 
                    ts.end_time AS end_time, 
                    ts.is_available AS is_available,
                    u.firstname AS firstname, 
                    u.lastname AS lastname,
                    sp.name AS speciality_name
                FROM timeslots ts
                JOIN doctors d ON ts.id_doctor = d.id
                JOIN users u ON d.id_user = u.id
                JOIN specialities sp ON sp.id = d.id_speciality
                WHERE ts.is_available = 1
                ORDER BY ts.start_time ASC";

            $stmt = $this->pdo->query($sql);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllAvailableTimeslots: " . $e->getMessage());
            return [];
        }
    }
    public function getAvailableTimeslotsByDoctor($doctorId): array {
        try {
            $sql = "SELECT ts.*, u.firstname, u.lastname,sp.name AS speciality_name 
                    FROM timeslots ts
                    JOIN doctors d ON ts.id_doctor = d.id
                    JOIN users u ON d.id_user = u.id
                    JOIN specialities sp ON sp.id = d.id_speciality
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
            $sql = "SELECT ts.*, u.firstname, u.lastname,sp.name AS speciality_name 
                    FROM timeslots ts
                    JOIN doctors d ON ts.id_doctor = d.id
                    JOIN users u ON d.id_user = u.id
                    JOIN specialities sp ON sp.id = d.id_speciality
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

    public function markTimeslotReserved($id):void{
        try{
            $sql = "UPDATE timeslots SET is_available = false
                    WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
        }catch(PDOException $e){
            echo "Error :".$e->getMessage();
        }
    }
}