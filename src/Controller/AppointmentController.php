<?php

namespace Controller;

use Cassandra\Time;
use Repositories\AppointmentRepository;
use Repositories\TimeslotRepository;

class AppointmentController
{
    private AppointmentRepository $appointmentRepo;
    private TimeslotRepository $timeslotRepo;
    public function __construct() {
        $this->appointmentRepo = new AppointmentRepository();
        $this->timeslotRepo = new TimeslotRepository();
    }

    public function book():void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: patient-page.php");
            exit();
        }

        $doctorId = isset($_POST['id_doctor']) ? (int)$_POST['id_doctor'] : 0;
        $timeslotId = isset($_POST['id_timeslot']) ? (int)$_POST['id_timeslot'] : 0;

        $patientId = $_SESSION['user_id'];

        if ($doctorId > 0 && $timeslotId > 0) {
            $success = $this->appointmentRepo->bookAppointment($patientId, $doctorId, $timeslotId);

            if ($success) {
                $this->timeslotRepo->markTimeslotReserved($timeslotId);
                header("Location: patient-page.php?success=1");
                exit();
            } else {
                header("Location: patient-page.php?error=booking_failed");
                exit();
            }
        } else {
            header("Location: patient-page.php?error=invalid_data");
            exit();
        }
    }

    public function cancel(): void {
        $appointmentId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $patientId = $_SESSION['user_id'];

        if ($appointmentId > 0) {
            $success = $this->appointmentRepo->cancelAppointment($appointmentId, $patientId);

            if ($success) {
                header("Location: patient-page.php?success=cancelled");
            } else {
                header("Location: patient-page.php?error=cancel_failed");
            }
            exit();
        }
    }
}