<?php
namespace Entities;
class Doctor
{
    private ?int $id;
    private User $user;
    private Speciality $speciality;
    private bool $is_active;

    public function __construct(User $user,Speciality $speciality,bool $is_active,?int $id = null){
        $this->user = $user;
        $this->speciality = $speciality;
        $this->is_active = $is_active;
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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getSpeciality(): Speciality
    {
        return $this->speciality;
    }

    public function setSpeciality(Speciality $speciality): void
    {
        $this->speciality = $speciality;
    }

    public function isIsActive(): bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): void
    {
        $this->is_active = $is_active;
    }
}