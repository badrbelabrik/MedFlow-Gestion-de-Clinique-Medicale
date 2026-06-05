<?php

namespace Repositories;

use Entities\Appointment;
use config\Database;
use Entities\Doctor;
use PDO;
use PDOException;
use Entities\Speciality;
use Entities\Timeslot;
use Entities\User;

class AppointmentRepository
{
    private PDO $pdo;
    public function __construct(){
        $this->pdo = Database::getConnection();
    }
    public function getAppointmentsByPatientId(int $patientId): array {
        try {
            $sql = "SELECT 
                    ap.id AS appointment_id, ap.status AS appointment_status,
                    ts.id AS timeslot_id, ts.start_time, ts.end_time, ts.is_available,
                    p.id AS patient_id, p.firstname AS patient_fname, p.lastname AS patient_lname, p.email AS patient_email, p.phone AS patient_phone, p.role AS patient_role,
                    d.id AS doc_id, d.is_active AS doc_active,
                    du.id AS doc_user_id, du.firstname AS doc_fname, du.lastname AS doc_lname, du.email AS doc_email, du.phone AS doc_phone, du.role AS doc_role,
                    sp.id AS spec_id, sp.name AS spec_name, sp.description AS spec_desc
                FROM appointments ap
                JOIN timeslots ts ON ap.id_timeslot = ts.id
                JOIN users p ON ap.id_patient = p.id
                JOIN doctors d ON ap.id_doctor = d.id
                JOIN users du ON d.id_user = du.id
                LEFT JOIN specialities sp ON d.id_speciality = sp.id
                WHERE ap.id_patient = ?
                ORDER BY ts.start_time DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$patientId]);

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
            error_log("Error in getAppointmentsByPatientId: " . $e->getMessage());
            return [];
        }
    }

public function bookAppointment($patientId, $doctorId, $timeslotId):bool{
        try{
            $sql = "INSERT INTO appointments(id_patient,id_doctor,id_timeslot) VALUES(?,?,?)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $patientId,
                $doctorId,
                $timeslotId
            ]);
        }catch(PDOException $e){
            echo "Error :".$e->getMessage();
            return 0;
        }
    }

    public function cancelAppointment(int $appointmentId, int $patientId): bool {
        try {
            $this->pdo->beginTransaction();

            $sqlGetSlot = "SELECT id_timeslot FROM appointments WHERE id = ? AND id_patient = ? AND status != 'terminate'";
            $stmtGet = $this->pdo->prepare($sqlGetSlot);
            $stmtGet->execute([$appointmentId, $patientId]);
            $app = $stmtGet->fetch(PDO::FETCH_ASSOC);

            if (!$app) {
                $this->pdo->rollBack();
                return false;
            }

            $timeslotId = $app['id_timeslot'];

            $sqlUpdateApp = "UPDATE appointments SET status = 'cancelled' WHERE id = ?";
            $stmtUpdateApp = $this->pdo->prepare($sqlUpdateApp);
            $stmtUpdateApp->execute([$appointmentId]);

            $sqlReleaseSlot = "UPDATE timeslots SET is_available = 1 WHERE id = ?";
            $stmtRelease = $this->pdo->prepare($sqlReleaseSlot);
            $stmtRelease->execute([$timeslotId]);

            $this->pdo->commit();
            return true;

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error cancelling appointment: " . $e->getMessage());
            return false;
        }
    }
}