<?php
// templates/admin/dashboard.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MedFlow — Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6fb; }
        .stat-card { border-radius: 12px; color: #fff; padding: 1.2rem 1.5rem; }
        .nav-admin a { color: #495057; text-decoration: none; font-weight: 500; }
        .nav-admin a:hover { color: #0d6efd; }
    </style>
</head>
<body>

<!-- Navigation simple -->
<nav class="navbar navbar-light bg-white border-bottom px-4">
    <span class="navbar-brand fw-bold text-primary">🏥 MedFlow Admin</span>
    <div class="nav-admin d-flex gap-4">
        <a href="?action=dashboard">Dashboard</a>
        <a href="?action=medecins">Médecins</a>
        <a href="?action=specialites">Spécialités</a>
    </div>
</nav>

<div class="container py-4">
    <h2 class="mb-4">📊 Dashboard — Statistiques de la clinique</h2>

    <!-- Cartes globales -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card bg-primary">
                <div class="fs-2 fw-bold"><?= $stats['total_medecins'] ?></div>
                <div>Médecins actifs</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-success">
                <div class="fs-2 fw-bold"><?= $stats['total_patients'] ?></div>
                <div>Patients inscrits</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-info">
                <div class="fs-2 fw-bold"><?= $stats['total_rdv'] ?></div>
                <div>Total rendez-vous</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-secondary">
                <div class="fs-2 fw-bold"><?= $stats['rdv_termine'] ?></div>
                <div>RDV terminés</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-danger">
                <div class="fs-2 fw-bold"><?= $stats['rdv_annule'] ?> <small class="fs-6">(<?= $tauxAnnul ?>%)</small></div>
                <div>RDV annulés (taux d'annulation)</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-warning">
                <div class="fs-2 fw-bold"><?= $stats['rdv_en_attente'] ?></div>
                <div>En attente</div>
            </div>
        </div>
    </div>

    <!-- Tableau par médecin -->
    <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold">Activité par médecin</div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Médecin</th>
                        <th>Spécialité</th>
                        <th class="text-center">Total RDV</th>
                        <th class="text-center">Terminés</th>
                        <th class="text-center">Annulés</th>
                        <th class="text-center">En attente</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($statsParMed as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['medecin']) ?></td>
                        <td><?= htmlspecialchars($row['specialite']) ?></td>
                        <td class="text-center"><?= $row['total_rdv'] ?></td>
                        <td class="text-center text-success fw-semibold"><?= $row['termines'] ?></td>
                        <td class="text-center text-danger"><?= $row['annules'] ?></td>
                        <td class="text-center text-warning"><?= $row['en_attente'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
