<?php // templates/admin/specialites_list.php ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MedFlow — Spécialités</title>
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
           class="text-decoration-none text-secondary">
           Médecins
        </a>

        <a href="?action=specialites"
           class="text-decoration-none text-primary fw-semibold">
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

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2 class="mb-0">
            🔬 Gestion des spécialités
        </h2>

        <a href="?action=specialite_create"
           class="btn btn-primary">
           + Ajouter
        </a>

    </div>

    <div class="card shadow-sm">

        <div class="card-body p-0">

            <table class="table table-hover mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($specialites as $s): ?>

                    <tr>

                        <td class="fw-semibold">
                            <?= htmlspecialchars($s['name']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($s['description'] ?? '—') ?>
                        </td>

                        <td>

                            <a href="?action=specialite_edit&id=<?= $s['id'] ?>"
                               class="btn btn-sm btn-outline-primary">
                               Modifier
                            </a>

                            <a href="?action=specialite_delete&id=<?= $s['id'] ?>"
                               class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Supprimer cette spécialité ?')">
                               Supprimer
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