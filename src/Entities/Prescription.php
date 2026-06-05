<?php
namespace Entities;
class Prescription
{
    private ?int $id;
    private string $description;
    private int $id_appointment;

    public function __construct(string $description,int $id_appointment,?int $id){
        $this->description = $description;
        $this->id_appointment = $id_appointment;
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getIdAppointment(): int
    {
        return $this->id_appointment;
    }

    public function setIdAppointment(int $id_appointment): void
    {
        $this->id_appointment = $id_appointment;
    }

}