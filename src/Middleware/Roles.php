<?php

class Role {
    public const ADMIN = "admin";
    public const DOCTOR = "doctor";
    public const PATIENT = "patient";

    public static function all(): array {
        return [
            
            self::ADMIN,
            self::DOCTOR,
            self::PATIENT
        ];
    }
}