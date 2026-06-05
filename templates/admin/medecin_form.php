<?php
$isEdit = isset($medecin);
$titre = $isEdit ? 'Modifier le médecin' : 'Ajouter un médecin';
$action = $isEdit ? '?action=medecin_update' : '?action=medecin_store';
require_once __DIR__ . '/../../config/bootstrap.php';

Middleware\AuthMiddleware::checkRoles(['admin']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MedFlow — <?= $titre ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-light bg-white border-bottom px-4">
    <span class="navbar-brand fw-bold text-primary">🏥 MedFlow Admin</span>
</nav>

<div class="container py-4" style="max-width:600px">

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold">
            <?= $titre ?>
        </div>

        <div class="card-body">

            <form method="POST" action="<?= $action ?>">

                <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?= $medecin['id'] ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Prénom</label>
                    <input
                        type="text"
                        name="firstname"
                        class="form-control"
                        required
                        value="<?= htmlspecialchars($medecin['firstname'] ?? $_POST['firstname'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input
                        type="text"
                        name="lastname"
                        class="form-control"
                        required
                        value="<?= htmlspecialchars($medecin['lastname'] ?? $_POST['lastname'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        required
                        value="<?= htmlspecialchars($medecin['email'] ?? $_POST['email'] ?? '') ?>">
                </div>

                <?php if (!$isEdit): ?>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Téléphone</label>
                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="<?= htmlspecialchars($medecin['phone'] ?? $_POST['phone'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Spécialité
                    </label>

                    <select
                        name="id_speciality"
                        class="form-select"
                        required>

                        <option value="">
                            -- Choisir --
                        </option>

                        <?php foreach ($specialites as $s): ?>

                            <option
                                value="<?= $s['id'] ?>"
                                <?= (($medecin['id_speciality'] ?? $_POST['id_speciality'] ?? '') == $s['id']) ? 'selected' : '' ?>>

                                <?= htmlspecialchars($s['name']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <?= $isEdit ? 'Mettre à jour' : 'Créer' ?>
                </button>

                <a href="?action=medecins" class="btn btn-secondary">
                    Retour
                </a>

            </form>

        </div>
    </div>

</div>

</body>
</html>