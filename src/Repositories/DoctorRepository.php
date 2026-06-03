<?php

namespace Repositories;

use Config\Database;
use Doctor;
use PDO;
use PDOException;
use Speciality;
use User;

class DoctorRepository
{
    private PDO $pdo;

    public function __construct(){
        $this->pdo = Database::getConnection();
    }

    public function getDoctorByName(string $firstname,string $lastname):?Doctor{
        try{
            $sql = "SELECT u.id AS user_id, u.firstname, u.lastname, u.email, u.phone, u.role,
                       sp.id AS speciality_id, sp.name AS speciality_name, sp.description AS speciality_desc,
                        d.is_active,d.id AS id_doctor
                FROM users u
                JOIN doctors d ON d.id_user = u.id
                LEFT JOIN specialities sp ON sp.id = d.id_speciality
                WHERE (u.firstname LIKE ? OR u.lastname LIKE ?) AND u.role = 'doctor'";
            $stmt = $this->pdo->prepare($sql);
            $searchTermFirst = '%' . $firstname . '%';
            $searchTermLast = '%' . $lastname . '%';
            $stmt->execute([
                $searchTermFirst,
                $searchTermLast
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $user = new User(
                $result['firstname'],
                $result['lastname'],
                $result['email'],
                $result['phone'],
                $result['role'],
                $result['user_id']
            );
            $speciality = new Speciality(
                $result['speciality_name'],
                $result['speciality_desc'],
                $result['speciality_id']
            );
            return new Doctor(
                $user,
                $speciality,
                $result['is_active'],
                $result['id_doctor']
            );

        }catch(PDOException $e){
            echo "Error :".$e->getMessage();
            return null;
        }
    }
}