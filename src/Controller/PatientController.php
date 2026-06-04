<?php

namespace Controller;

use Repositories\DoctorRepository;
use Repositories\SpecialityRepository;
use Repositories\AppointmentRepository;
use Repositories\TimeslotRepository;

class PatientController
{
    private DoctorRepository $doctorRepo;
    private SpecialityRepository $specialityRepo;
    private AppointmentRepository $appointmentRepo;
    private TimeslotRepository $timeslotRepo;

    public function __construct() {
        $this->doctorRepo = new DoctorRepository();
        $this->specialityRepo = new SpecialityRepository();
        $this->appointmentRepo = new AppointmentRepository();
        $this->timeslotRepo = new TimeslotRepository();
    }


    public function dashboard(): array {
        $allSpecialities = $this->specialityRepo->getAllSpecialities();

        $patientId = 5; // ID de test
        $myAppointments = $this->appointmentRepo->getAppointmentsByPatientId($patientId);

        $firstname = isset($_GET['firstname']) ? trim($_GET['firstname']) : '';
        $lastname  = isset($_GET['lastname']) ? trim($_GET['lastname']) : '';
        $specialityId = isset($_GET['speciality_id']) ? trim($_GET['speciality_id']) : '';

        $doctors = $this->doctorRepo->getAllDoctors() ?? [];

        if ($firstname !== '' || $lastname !== '' || $specialityId !== '') {
            $doctors = array_filter($doctors, function($doctor) use ($firstname, $lastname, $specialityId) {
                $docUser = $doctor->getUser();
                $docSpec = $doctor->getSpeciality();

                if ($firstname !== '' && stripos($docUser->getFirstname(), $firstname) === false) return false;
                if ($lastname !== '' && stripos($docUser->getLastname(), $lastname) === false) return false;
                if ($specialityId !== '' && (string)$docSpec->getId() !== (string)$specialityId) return false;

                return true;
            });
        }
        $timeSlots = [];
        if($firstname == '' && $lastname == '' && $specialityId == ''){
            $timeSlots = $this->timeslotRepo->get
        }
        else if($firstname !== '' && $lastname !== ''){
            $doctorId = $this->doctorRepo->getDoctorByName($firstname,$lastname)->getId();
            $timeSlots = $this->timeslotRepo->getAvailableTimeslotsByDoctor($doctorId);
        } else if($firstname == '' && $lastname == '' && $specialityId !== ''){
            $timeSlots = $this->timeslotRepo->getTimeSlotsBySpeciality($specialityId);
        }
        return [
            'allSpecialities' => $allSpecialities,
            'doctors' => $doctors,
            'myAppointments' => $myAppointments,
            'timeSlots' => $timeSlots
        ];
    }
}