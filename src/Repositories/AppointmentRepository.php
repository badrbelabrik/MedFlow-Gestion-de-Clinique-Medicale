<?php

namespace Repositories;

use Appointment;
use Config\Database;
use Doctor;
use PDO;
use PDOException;
use Speciality;
use Timeslot;
use User;

class AppointmentRepository
{
    private PDO $pdo;
    public function __construct(){
        $this->pdo = Database::getConnection();
    }
    public function getAppointmentsByDoctorId(int $doctorId): array {
        try {
            // Requête ajustée selon ton nouveau schéma
            $sql = "SELECT 
                    ap.id AS appointment_id, ap.status AS appointment_status,
                    ts.id AS timeslot_id, ts.start_time, ts.end_time, ts.is_available,
                    p.id AS patient_id, p.firstname AS patient_fname, p.lastname AS patient_lname, p.email AS patient_email, p.phone AS patient_phone, p.role AS patient_role,
                    d.id AS doc_id, d.is_active AS doc_active,
                    du.id AS doc_user_id, du.firstname AS doc_fname, du.lastname AS doc_lname, du.email AS doc_email, du.phone AS doc_phone, du.role AS doc_role,
                    sp.id AS spec_id, sp.name AS spec_name, sp.description AS spec_desc
                FROM appointments ap
                JOIN timeslots ts ON ap.id_timeslot = ts.id
                JOIN users p ON ap.id_patient = p.id              -- Le patient (depuis la table users)
                JOIN doctors d ON ap.id_doctor = d.id             -- Le médecin (depuis la table doctors)
                JOIN users du ON d.id_user = du.id                -- Les infos privées du médecin (depuis users)
                LEFT JOIN specialities sp ON d.id_speciality = sp.id
                WHERE ap.id_doctor = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$doctorId]);

            $appointments = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $patient = new User(
                    $row['patient_fname'],
                    $row['patient_lname'],
                    $row['patient_email'],
                    $row['patient_phone'],
                    $row['patient_role'],
                    $row['patient_id']
                );

                $speciality = new Speciality(
                    $row['spec_name'],
                    $row['spec_desc'],
                    $row['spec_id']
                );

                $doctorUser = new User(
                    $row['doc_fname'],
                    $row['doc_lname'],
                    $row['doc_email'],
                    $row['doc_phone'],
                    $row['doc_role'],
                    $row['doc_user_id']
                );

                $doctor = new Doctor(
                    $doctorUser,
                    $speciality,
                    (bool)$row['doc_active'],
                    $row['doc_id']
                );

                $timeslot = new Timeslot(
                    $row['start_time'],
                    $row['end_time'],
                    (bool)$row['is_available'],
                    $row['timeslot_id']
                );


                $appointments[] = new Appointment(
                    $patient,
                    $doctor,
                    $row['appointment_status'],
                    $timeslot,
                    $row['appointment_id']
                );
            }

            return $appointments;

        } catch (PDOException $e) {
            error_log("Error in getAppointmentsByDoctorId: " . $e->getMessage());
            return [];
        }
    }
}