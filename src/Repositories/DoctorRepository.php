<?php

namespace Repositories;

use config\Database;
use PDO;
use Entities\Doctor;
use PDOException;
use Entities\Speciality;
use Entities\User;

class DoctorRepository
{
    private PDO $pdo;

    public function __construct(){
        $this->pdo = Database::getConnection();
    }

    public function getDoctorByName(string $firstname, string $lastname): ?Doctor {
        try {
            $sql = "SELECT u.id AS user_id, u.firstname, u.lastname, u.email, u.phone, u.role,
                       sp.id AS speciality_id, sp.name AS speciality_name, sp.description AS speciality_desc,
                       d.is_active, d.id AS id_doctor
                FROM users u
                JOIN doctors d ON d.id_user = u.id
                LEFT JOIN specialities sp ON sp.id = d.id_speciality
                WHERE (u.firstname LIKE ? OR u.lastname LIKE ?) AND u.role = 'doctor'
                LIMIT 1";

            $stmt = $this->pdo->prepare($sql);

            $searchTermFirst = $firstname !== '' ? '%' . $firstname . '%' : '%';
            $searchTermLast  = $lastname !== '' ? '%' . $lastname . '%' : '%';

            $stmt->execute([$searchTermFirst, $searchTermLast]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                return null;
            }

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

        } catch (PDOException $e) {
            error_log("Error in getDoctorByName: " . $e->getMessage());
            return null;
        }
    }

    public function getAllDoctors(): array {
        try {
            $sql = "SELECT d.id AS doctor_id, d.is_active,
                       u.id AS user_id, u.firstname, u.lastname, u.email, u.phone, u.role,
                       sp.id AS speciality_id, sp.name AS speciality_name, sp.description AS speciality_desc
                FROM doctors d
                JOIN users u ON d.id_user = u.id
                JOIN specialities sp ON d.id_speciality = sp.id";

            $stmt = $this->pdo->query($sql);

            $doctors = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $user = new User(
                    $row['firstname'],
                    $row['lastname'],
                    $row['email'],
                    $row['phone'],
                    $row['role'],
                    $row['user_id']
                );


                $speciality = new Speciality(
                    $row['speciality_name'],
                    $row['speciality_desc'],
                    $row['speciality_id']
                );


                $doctors[] = new Doctor(
                    $user,
                    $speciality,
                    (bool)$row['is_active'],
                    $row['doctor_id']
                );
            }

            return $doctors;

        } catch (PDOException $e) {
            error_log("Error in getAllDoctors: " . $e->getMessage());
            return [];
        }
    }

    public function getDoctorByUserId(int $userId): ?array
    {
        $query = "SELECT d.id AS doctor_id, d.id_user, d.id_speciality, d.is_active, 
                         s.name AS speciality_name,
                         u.firstname, u.lastname, u.email
                  FROM doctors d
                  JOIN users u ON d.id_user = u.id
                  JOIN specialities s ON d.id_speciality = s.id
                  WHERE d.id_user = :id_user LIMIT 1";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id_user' => $userId]);

        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

        return $doctor ?: null;
    }
}