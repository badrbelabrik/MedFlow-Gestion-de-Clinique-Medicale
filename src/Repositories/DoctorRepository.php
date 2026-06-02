<?php

namespace Repositories;

use Config\Database;
use Doctor;
use PDO;

class DoctorRepository
{
    private PDO $pdo;

    public function __construct(){
        $this->pdo = Database::getConnection();
    }

    public function getDoctorByName(string $firstname,string $lastname):?Doctor{
        try{
            $sql = "SELECT d.id AS doctor_id,u.id AS user_id,u.firstname,u.lastname,sp.id AS speciality_id,sp.name AS speciality_name FROM users u
                    JOIN doctors d ON d.id_user = u.id
                    JOIN specialities sp ON sp.id = d.id_speciality
                    WHERE u.firstname = ? OR u.lastname = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $firstname,
                $lastname
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($result){
                return new Doctor(
                    
                );
            }

        }catch(PDOException $e){
            echo "Error :".$e->getMessage();
            return null;
        }
    }
}