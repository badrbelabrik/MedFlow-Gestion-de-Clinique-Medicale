<?php
namespace Entities;
class Appointment
{
    private ?int $id;
    private User $patient;
    private Doctor $doctor;
    private string $status;
    private Timeslot $timeslot;

    public function __construct(User $patient, Doctor $doctor, string $status, Timeslot $timeslot, ?int $id = null) {
        $this->patient = $patient;
        $this->doctor = $doctor;
        $this->status = $status;
        $this->timeslot = $timeslot;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getPatient(): User
    {
        return $this->patient;
    }

    public function setPatient(User $patient): void
    {
        $this->patient = $patient;
    }

    public function getDoctor(): Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(Doctor $doctor): void
    {
        $this->doctor = $doctor;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getTimeslot(): Timeslot
    {
        return $this->timeslot;
    }

    public function setTimeslot(Timeslot $timeslot): void
    {
        $this->timeslot = $timeslot;
    }


}