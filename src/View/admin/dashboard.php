<?php

use Controller\AdminController;

require_once __DIR__ . '/../../Controller/AdminController.php';

$admin = new AdminController();
$stats = $admin->getStats();
$doctors = $admin->getDoctors();
$specialities = $admin->getSpecialities();

require_once __DIR__ . '/../../../config/bootstrap.php';

Middleware\AuthMiddleware::checkRoles(['admin']);

?>
<div class="admin-dashboard">
    <h1>Admin Dashboard</h1>

    <div class="stats">
        <div>Total Doctors: <?php echo htmlspecialchars($stats['total_doctors']); ?></div>
        <div>Total Specialities: <?php echo htmlspecialchars($stats['total_specialities']); ?></div>
        <div>Total Appointments: <?php echo htmlspecialchars($stats['total_appointments']); ?></div>
        <div>Total Patients: <?php echo htmlspecialchars($stats['total_patients']); ?></div>
    </div>

    <h2>Doctors</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Speciality</th>
                <th>Active</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($doctors as $doc): ?>
            <tr>
                <td><?php echo htmlspecialchars($doc->id); ?></td>
                <td><?php echo htmlspecialchars($doc->firstname . ' ' . $doc->lastname); ?></td>
                <td><?php echo htmlspecialchars($doc->email); ?></td>
                <td><?php echo htmlspecialchars($doc->specialityName); ?></td>
                <td><?php echo $doc->isActive ? 'Yes' : 'No'; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Specialities</h2>
    <ul>
        <?php foreach ($specialities as $s): ?>
            <li><?php echo htmlspecialchars($s->name); ?> - <?php echo htmlspecialchars($s->description); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
