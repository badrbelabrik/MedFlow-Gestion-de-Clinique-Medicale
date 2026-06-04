<?php

require_once __DIR__ . "/../Repository/UserRepository.php";

class AuthController {

    private UserRepository $repo;

    public function __construct() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->repo = new UserRepository();
    }

    public function login($email, $password) {

        $user = $this->repo->findByEmail($email);

        if ($user && password_verify($password, $user["password"])) {

            $_SESSION["user"] = [
                "id" => $user["id"],
                "name" => $user["name"],
                "email" => $user["email"],
                "role" => $user["role"]
            ];

            return "Login success";
        }

        return "Invalid credentials";
    }

    public function logout() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();

        return "Logged out";
    }
}