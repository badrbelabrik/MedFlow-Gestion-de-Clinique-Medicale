<?php

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../Entities/User.php";


class UserRepository {



    private PDO $db;


    
    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create(User $user) {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)"
        );

        $stmt->execute([
            $user->getName(),
            $user->getEmail(),
            password_hash($user->getPassword(), PASSWORD_DEFAULT),
            $user->getRole()
        ]);
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}