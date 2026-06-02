<?php

class Appointment
{
    private ?int $id;
    private int $id_patient;
    private int $id_doctor;
    private string $status;
    private int $id_timeslot;

    public function __construct(int $id_patient,int $id_doctor,string $status,int $id_timeslot,?int $id = null){
        $this->id_patient = $id_patient;
        $this->id_doctor = $id_doctor;
        $this->status = $status;
        $this->id_timeslot = $id_timeslot;
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

    public function getIdPatient(): int
    {
        return $this->id_patient;
    }

    public function setIdPatient(int $id_patient): void
    {
        $this->id_patient = $id_patient;
    }

    public function getIdDoctor(): int
    {
        return $this->id_doctor;
    }

    public function setIdDoctor(int $id_doctor): void
    {
        $this->id_doctor = $id_doctor;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getIdTimeslot(): int
    {
        return $this->id_timeslot;
    }

    public function setIdTimeslot(int $id_timeslot): void
    {
        $this->id_timeslot = $id_timeslot;
    }

}