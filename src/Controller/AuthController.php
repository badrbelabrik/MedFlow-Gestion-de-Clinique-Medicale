<?php

namespace Controller;

use Repositories\UserRepository;

class AuthController
{
    private UserRepository $userRepo;

    public function __construct(){
        $this->userRepo = new UserRepository();
    }


    public function login(): ?string {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                return "Veuillez remplir tous les champs.";
            }


            $user = $this->userRepo->verifyLogin($email, $password);

            if ($user) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['user_id'] = $user->getId();
                $_SESSION['user_firstname'] = $user->getFirstname();
                $_SESSION['user_lastname'] = $user->getLastname();
                $_SESSION['user_email'] = $user->getEmail();
                $_SESSION['user_phone'] = $user->getPhone();
                $_SESSION['user_role'] = $user->getRole();

                if ($_SESSION['user_role'] === 'patient') {
                    header('Location: ../templates/patient/patient-page.php');
                } else if($_SESSION['user_role'] === 'doctor'){
                    header('Location: ../templates/doctor/dashboard.php');
                } else if($_SESSION['user_role'] === 'admin'){
                    header('Location: ../src/View/admin/dashboard.php');
                }
                exit();

            } else {

                return "Identifiants incorrects.";
            }
        }
        return null;
    }

    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();
        header('Location: /MedFlow-Gestion-de-Clinique-Medicale/public/index.php');
        exit();
    }
}