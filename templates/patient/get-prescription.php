<?php
spl_autoload_register(function ($class) {
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $paths = [
        __DIR__ . '/../../src/' . $classPath . '.php',
        __DIR__ . '/../../config/' . basename($classPath) . '.php'
    ];
    foreach ($paths as $file) { if (file_exists($file)) { require_once $file; return; } }
});


use Repositories\PrescriptionRepository;

header('Content-Type: application/json; charset=utf-8');

$appointmentId = isset($_GET['appointment_id']) ? (int)$_GET['appointment_id'] : 0;
$patientId = $_SESSION['user_id'];

if ($appointmentId > 0) {
    $repo = new PrescriptionRepository();
    $prescription = $repo->getPrescriptionByAppointment($appointmentId, $patientId);

    if ($prescription) {
        echo json_encode([
            'success' => true,
            'description' => $prescription['description']
        ]);
        exit();
    }
}

echo json_encode([
    'success' => false,
    'message' => 'Aucune ordonnance trouvée pour ce rendez-vous.'
]);
exit();