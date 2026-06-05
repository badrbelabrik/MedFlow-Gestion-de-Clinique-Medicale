<?php
namespace Controller;
use Exception;
use MedecinRepository;
use SpecialiteRepository;
use StatsRepository;

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Repository/MedecinRepository.php';
require_once __DIR__ . '/../Repository/SpecialiteRepository.php';
require_once __DIR__ . '/../Repository/StatsRepository.php';

class AdminController
{
    private MedecinRepository $medecinRepo;
    private SpecialiteRepository $specialiteRepo;
    private StatsRepository $statsRepo;

    public function __construct()
    {
        $this->medecinRepo = new MedecinRepository();
        $this->specialiteRepo = new SpecialiteRepository();
        $this->statsRepo = new StatsRepository();
    }

    public function handle(): void
    {
        $action = $_GET['action'] ?? 'dashboard';

        switch ($action) {

            case 'dashboard':
                $this->dashboard();
                break;

            case 'medecins':
                $this->listMedecins();
                break;

            case 'medecin_create':
                $this->createMedecin();
                break;

            case 'medecin_store':
                $this->storeMedecin();
                break;

            case 'medecin_edit':
                $this->editMedecin();
                break;

            case 'medecin_update':
                $this->updateMedecin();
                break;

            case 'medecin_toggle':
                $this->toggleMedecin();
                break;

            case 'specialites':
                $this->listSpecialites();
                break;

            case 'specialite_create':
                $this->createSpecialite();
                break;

            case 'specialite_store':
                $this->storeSpecialite();
                break;

            case 'specialite_edit':
                $this->editSpecialite();
                break;

            case 'specialite_update':
                $this->updateSpecialite();
                break;

            case 'specialite_delete':
                $this->deleteSpecialite();
                break;

            default:
                $this->dashboard();
                break;
        }
    }

    // =========================
    // Dashboard
    // =========================

    private function dashboard(): void
    {
        $stats = $this->statsRepo->getGlobalStats();
        $statsParMed = $this->statsRepo->getRdvParMedecin();
        $tauxAnnul = $this->statsRepo->getTauxAnnulation();

        require __DIR__ . '/../../templates/admin/dashboard.php';
    }

    // =========================
    // Doctors
    // =========================

    private function listMedecins(): void
    {
        $medecins = $this->medecinRepo->findAll();

        require __DIR__ . '/../../templates/admin/medecins_list.php';
    }

    private function createMedecin(): void
    {
        $specialites = $this->specialiteRepo->findAll();

        require __DIR__ . '/../../templates/admin/medecin_form.php';
    }

    private function storeMedecin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=medecins');
            exit;
        }

        try {

            $this->medecinRepo->create($_POST);

            header('Location: ?action=medecins&success=1');
            exit;

        } catch (Exception $e) {

            $error = $e->getMessage();
            $specialites = $this->specialiteRepo->findAll();

            require __DIR__ . '/../../templates/admin/medecin_form.php';
        }
    }

    private function editMedecin(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        $medecin = $this->medecinRepo->findById($id);

        if (!$medecin) {
            header('Location: ?action=medecins');
            exit;
        }

        $specialites = $this->specialiteRepo->findAll();

        require __DIR__ . '/../../templates/admin/medecin_form.php';
    }

    private function updateMedecin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=medecins');
            exit;
        }

        try {

            $this->medecinRepo->update(
                (int) $_POST['id'],
                $_POST
            );

            header('Location: ?action=medecins&success=1');
            exit;

        } catch (Exception $e) {

            $error = $e->getMessage();

            $medecin = $this->medecinRepo->findById(
                (int) $_POST['id']
            );

            $specialites = $this->specialiteRepo->findAll();

            require __DIR__ . '/../../templates/admin/medecin_form.php';
        }
    }

    private function toggleMedecin(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        $this->medecinRepo->toggleActif($id);

        header('Location: ?action=medecins');
        exit;
    }

    // =========================
    // Specialities
    // =========================

    private function listSpecialites(): void
    {
        $specialites = $this->specialiteRepo->findAll();

        require __DIR__ . '/../../templates/admin/specialites_list.php';
    }

    private function createSpecialite(): void
    {
        require __DIR__ . '/../../templates/admin/specialite_form.php';
    }

    private function storeSpecialite(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=specialites');
            exit;
        }

        try {

            $this->specialiteRepo->create(
                $_POST['name'],
                $_POST['description'] ?? ''
            );

            header('Location: ?action=specialites&success=1');
            exit;

        } catch (Exception $e) {

            $error = $e->getMessage();

            require __DIR__ . '/../../templates/admin/specialite_form.php';
        }
    }

    private function editSpecialite(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        $specialite = $this->specialiteRepo->findById($id);

        if (!$specialite) {
            header('Location: ?action=specialites');
            exit;
        }

        require __DIR__ . '/../../templates/admin/specialite_form.php';
    }

    private function updateSpecialite(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=specialites');
            exit;
        }

        try {

            $this->specialiteRepo->update(
                (int) $_POST['id'],
                $_POST['name'],
                $_POST['description'] ?? ''
            );

            header('Location: ?action=specialites&success=1');
            exit;

        } catch (Exception $e) {

            $error = $e->getMessage();

            $specialite = $this->specialiteRepo->findById(
                (int) $_POST['id']
            );

            require __DIR__ . '/../../templates/admin/specialite_form.php';
        }
    }

    private function deleteSpecialite(): void
    {
        try {

            $this->specialiteRepo->delete(
                (int) ($_GET['id'] ?? 0)
            );

            header('Location: ?action=specialites&success=1');
            exit;

        } catch (Exception $e) {

            header(
                'Location: ?action=specialites&error=' .
                urlencode($e->getMessage())
            );

            exit;
        }
    }
}