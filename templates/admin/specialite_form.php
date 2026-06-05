<?php
$isEdit = isset($specialite);
$titre = $isEdit ? 'Modifier la spécialité' : 'Ajouter une spécialité';
$action = $isEdit ? '?action=specialite_update' : '?action=specialite_store';


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

<div class="container py-4" style="max-width:550px">

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
                    <input type="hidden" name="id" value="<?= $specialite['id'] ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">
                        Nom
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        required
                        value="<?= htmlspecialchars($specialite['name'] ?? $_POST['name'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="3"><?= htmlspecialchars($specialite['description'] ?? $_POST['description'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <?= $isEdit ? 'Enregistrer' : 'Créer' ?>
                </button>

                <a href="?action=specialites"
                   class="btn btn-secondary">
                    Retour
                </a>

            </form>

        </div>

    </div>

</div>

</body>
</html>