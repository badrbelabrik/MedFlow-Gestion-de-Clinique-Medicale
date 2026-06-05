<?php

// ── Système de sections ────────────────────────────────────────────────────────

$sections        = [];
$currentSection  = null;

function startSection(string $name): void {
    global $currentSection;
    $currentSection = $name;
    ob_start();
}

function endSection(): void {
    global $sections, $currentSection;
    $sections[$currentSection] = ob_get_clean();
    $currentSection = null;
}

function section(string $name, string $default = ''): string {
    global $sections;
    return $sections[$name] ?? $default;
}

// Les templates appellent startSection / endSection AVANT ce require,
// donc on collecte d'abord le contenu, puis on l'insère ci-dessous.
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars(section('title', 'MedFlow')) ?></title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    <style>
        body { background-color: #f4f6fb; }

        /* Sidebar */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: #1a2942;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
        }
        .sidebar .brand {
            padding: 1.5rem 1.25rem;
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.7);
            padding: .65rem 1.25rem;
            border-radius: .375rem;
            margin: .15rem .5rem;
            transition: background .15s, color .15s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,.1);
            color: #fff;
        }
        .main-content {
            margin-left: 240px;
            min-height: 100vh;
        }

        /* Badges */
        .bg-opacity-15 { --bs-bg-opacity: .15; }
    </style>
</head>
<body>

<!-- ─── Sidebar ────────────────────────────────────────────────────────────── -->
<aside class="sidebar">
    <div class="brand">
        <i class="bi bi-heart-pulse me-2"></i>MedFlow
    </div>
    <nav class="nav flex-column pt-3">
        <a href="/doctor/dashboard"
           class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'dashboard') ? 'active' : '' ?>">
            <i class="bi bi-grid me-2"></i>Dashboard
        </a>
        <a href="/doctor/appointments"
           class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'appointments') ? 'active' : '' ?>">
            <i class="bi bi-calendar-check me-2"></i>Rendez-vous
        </a>
    </nav>
    <div class="mt-auto p-3 border-top border-white border-opacity-10">
        <div class="text-white-50 small mb-2">
            <i class="bi bi-person-circle me-1"></i>
            <?= htmlspecialchars(
                ($_SESSION['user']['firstname'] ?? '') . ' ' .
                ($_SESSION['user']['lastname']  ?? '')
            ) ?>
        </div>
        <a href="/logout" class="btn btn-sm btn-outline-light w-100">
            <i class="bi bi-box-arrow-right me-1"></i>Déconnexion
        </a>
    </div>
</aside>

<!-- ─── Contenu principal ──────────────────────────────────────────────────── -->
<main class="main-content">
    <?= section('content') ?>
</main>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>