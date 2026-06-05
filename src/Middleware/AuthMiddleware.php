<?php

namespace Middleware;

class AuthMiddleware
{
    private static function startSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function checkRoles(array $allowedRoles): void {
        self::startSession();


        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
            header('Location: /MedFlow-Gestion-de-Clinique-Medicale/public/index.php');
            exit();
        }

        if (!in_array($_SESSION['user_role'], $allowedRoles)) {
            http_response_code(403);
            die("<h1>403 - Accès Interdit</h1><p>Votre rôle (" . htmlspecialchars($_SESSION['user_role']) . ") ne vous donne pas accès à cette page.</p>");
        }
    }
}