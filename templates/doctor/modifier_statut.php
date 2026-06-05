<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header('Location: ../auth/login.php');
    exit();
}

// Khdem b class Connexion dyalk (Exemple b PDO)
try {
    $pdo = new PDO("mysql:host=localhost;dbname=medflow;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$id_rdv = isset($_GET['id_rdv']) ? intval($_GET['id_rdv']) : 0;
$status = isset($_GET['status']) ? $_GET['status'] : '';

// Validation dyal l-status match m3a ENUM dyalk
if ($id_rdv > 0 && in_array($status, ['pending', 'confirmed', 'cancelled', 'terminate'])) {
    
    $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id_rdv]);
}

// Erj3 t7an l-page dyal rdv
header('Location: RendezVous.php');
exit();