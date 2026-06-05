<?php
// public/admin.php  — point d'entrée du module Admin
// À placer dans public/ et accéder via : http://localhost/medflow/public/admin.php

session_start();

// TODO: Ajouter ici le middleware RBAC
// require_once __DIR__ . '/../src/Middleware/AuthMiddleware.php';
// AuthMiddleware::requireRole('admin');

require_once __DIR__ . '/../src/Controller/AdminController.php';

$controller = new AdminController();
$controller->handle();
