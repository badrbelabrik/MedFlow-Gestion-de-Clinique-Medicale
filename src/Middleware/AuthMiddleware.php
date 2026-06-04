<?php

class AuthMiddleware {

    private static function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function checkLogin() {
        self::startSession();

        if (!isset($_SESSION["user"])) {
            header("Location: login.php");
            exit;
        }
    }

    public static function checkRole($role) {
        self::startSession();

        if (!isset($_SESSION["user"])) {
            header("Location: login.php");
            exit;
        }

        if ($_SESSION["user"]["role"] !== $role) {
            die("403 - Access denied");
        }
    }
}