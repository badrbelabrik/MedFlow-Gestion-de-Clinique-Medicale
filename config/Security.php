 <?php

require_once __DIR__ . "/../src/Enum/Role.php";

$permissions = [
    Role::ADMIN => ["all"],
    Role::DOCTOR => ["view_patients", "manage_rdv"],
    Role::PATIENT => ["book_rdv", "view_rdv"]
];