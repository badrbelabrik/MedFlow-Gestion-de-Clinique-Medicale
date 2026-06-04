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

        $patientId = 5;

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
}