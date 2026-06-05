<?php // templates/admin/medecins_list.php ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MedFlow — Médecins</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-light bg-white border-bottom px-4">
    <span class="navbar-brand fw-bold text-primary">🏥 MedFlow Admin</span>

    <div class="d-flex gap-4">
        <a href="?action=dashboard"
           class="text-decoration-none text-secondary">
           Dashboard
        </a>

        <a href="?action=medecins"
           class="text-decoration-none text-primary fw-semibold">
           Médecins
        </a>

        <a href="?action=specialites"
           class="text-decoration-none text-secondary">
           Spécialités
        </a>
    </div>
</nav>

<div class="container py-4">

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            ✅ Opération effectuée avec succès.
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2 class="mb-0">
            👨‍⚕️ Gestion des médecins
        </h2>

        <a href="?action=medecin_create"
           class="btn btn-primary">
           + Ajouter un médecin
        </a>

    </div>

    <div class="card shadow-sm">

        <div class="card-body p-0">

            <table class="table table-hover mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Nom complet</th>
                        <th>Email</th>
                        <th>Spécialité</th>
                        <th>Téléphone</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($medecins as $m): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars(
                                $m['firstname'] . ' ' . $m['lastname']
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($m['email']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($m['speciality']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($m['phone'] ?? '—') ?>
                        </td>

                        <td>

                            <?php if ($m['is_active']): ?>

                                <span class="badge bg-success">
                                    Actif
                                </span>

                            <?php else: ?>

                                <span class="badge bg-danger">
                                    Désactivé
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <a href="?action=medecin_edit&id=<?= $m['id'] ?>"
                               class="btn btn-sm btn-outline-primary">
                               Modifier
                            </a>

                            <a href="?action=medecin_toggle&id=<?= $m['id'] ?>"
                               class="btn btn-sm btn-outline-<?= $m['is_active'] ? 'danger' : 'success' ?>"
                               onclick="return confirm('Confirmer ?')">

                               <?= $m['is_active']
                                   ? 'Désactiver'
                                   : 'Réactiver' ?>

                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>