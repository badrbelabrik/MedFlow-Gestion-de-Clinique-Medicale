<?php
namespace Entities;
class Timeslot
{
    private ?int $id;
    private string $start_time;
    private string $end_time;
    private bool $is_available;
    private int $id_doctor;

    public function __construct(string $start_time,string $end_time,bool $is_available,int $id_doctor,?int $id = null){
        $this->start_time = $start_time;
        $this->end_time = $end_time;
        $this->is_available = $is_available;
        $this->id_doctor = $id_doctor;
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

    public function getStartTime(): string
    {
        return $this->start_time;
    }

    public function setStartTime(string $start_time): void
    {
        $this->start_time = $start_time;
    }

    public function getEndTime(): string
    {
        return $this->end_time;
    }

    public function setEndTime(string $end_time): void
    {
        $this->end_time = $end_time;
    }

    public function isIsAvailable(): bool
    {
        return $this->is_available;
    }

    public function setIsAvailable(bool $is_available): void
    {
        $this->is_available = $is_available;
    }

    public function getIdDoctor(): int
    {
        return $this->id_doctor;
    }

    public function setIdDoctor(int $id_doctor): void
    {
        $this->id_doctor = $id_doctor;
    }

}