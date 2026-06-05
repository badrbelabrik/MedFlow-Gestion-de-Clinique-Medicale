<?php

spl_autoload_register(function ($class) {
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $paths = [
            __DIR__ . '/../../src/' . $classPath . '.php',
            __DIR__ . '/../../config/' . basename($classPath) . '.php'
    ];
    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});



use Controller\PatientController;
use Controller\AppointmentController;
use Helpers\DateHelper;
require_once __DIR__ . '/../../config/bootstrap.php';

Middleware\AuthMiddleware::checkRoles(['patient']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserve'])) {
    $appointmentController = new AppointmentController();
    $appointmentController->book();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'cancel') {
    $appointmentController = new AppointmentController();
    $appointmentController->cancel();
}


$controller = new PatientController();
$data = $controller->dashboard();
extract($data);

$pageTitle = "Espace Patient — MedFlow";
include_once __DIR__ . '/../layout/header.php';
?>
    <main class="flex-grow max-w-4xl w-full mx-auto px-4 sm:px-6 py-10 space-y-12">

        <section class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-8">
            <div>
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest block mb-1">Prise de rendez-vous en ligne</span>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Rechercher un médecin</h2>
                <p class="text-xs text-slate-400 mt-1">Filtrez par spécialité ou par nom pour afficher les disponibilités en temps réel.</p>
            </div>

            <form action="patient-page.php" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Prénom :</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">🔍</span>
                        <input type="text" name="firstname" value="<?= htmlspecialchars($_GET['firstname'] ?? '') ?>" placeholder="Ex: Karim..." class="w-full border border-slate-200 bg-white rounded-xl pl-8 pr-3 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 transition">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nom de famille :</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">🔍</span>
                        <input type="text" name="lastname" value="<?= htmlspecialchars($_GET['lastname'] ?? '') ?>" placeholder="Ex: Bennani..." class="w-full border border-slate-200 bg-white rounded-xl pl-8 pr-3 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Spécialité médicale</label>
                    <select name="speciality_id" class="w-full border border-slate-200 bg-white rounded-xl p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 transition">
                        <option value="">Toutes les spécialités</option>
                        <?php foreach($allSpecialities as $speciality) : ?>
                            <option value="<?= $speciality['id'] ?>" <?= isset($_GET['speciality_id']) && $_GET['speciality_id'] == $speciality['id'] ? 'selected' : '' ?>><?= htmlspecialchars($speciality['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold py-2.5 rounded-xl transition shadow-sm h-[44px]">
                        Rechercher
                    </button>
                </div>
            </form>

            <div class="space-y-6">
                <?php if (empty($timeSlots)): ?>
                    <div class="text-center py-8 border border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                        <span class="text-2xl">🔍</span>
                        <p class="text-sm font-medium text-slate-500 mt-2">Aucun créneau horaire disponible ne correspond à vos critères.</p>
                    </div>
                <?php else: ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($timeSlots as $slot):

                            $start = DateHelper::formatTimeslotDate($slot['start_time']);
                            $end = new DateTime($slot['end_time']);

                            $docFirstname = $slot['firstname'] ?? 'Inconnu';
                            $docLastname = $slot['lastname'] ?? 'Médecin';
                            ?>

                            <div class="border border-slate-200/60 rounded-2xl p-5 bg-white space-y-4 shadow-sm hover:border-slate-300/80 transition flex flex-col justify-between">

                                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                                            Dr
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-slate-900">
                                                Dr. <?= htmlspecialchars($docFirstname . ' ' . $docLastname) ?>
                                            </h4>
                                            <p class="text-xs text-slate-400">
                                                <?=  $slot['speciality_name'] ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <form action="patient-page.php" method="POST" class="space-y-4">
                                    <input type="hidden" name="id_doctor" value="<?= $slot['id_doctor'] ?? '' ?>">
                                    <input type="hidden" name="id_timeslot" value="<?= $slot['id'] ?>">

                                    <div class="relative border border-emerald-500/30 bg-emerald-50/10 rounded-xl p-4 flex flex-col items-center justify-center">
                                        <span class="block text-xs font-bold text-emerald-900"><?= $start['date_texte'] ?></span>
                                        <span class="block text-[11px] font-medium text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded mt-1.5">
                                            <?= $start['heure'] ?> - <?= $end->format('H:i') ?>
                                        </span>
                                    </div>

                                    <button name="reserve" type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2.5 rounded-xl transition shadow-sm flex items-center justify-center space-x-2">
                                        <span>✨</span>
                                        <span>Réserver ce créneau</span>
                                    </button>
                                </form>

                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="space-y-4">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Suivi en temps réel</span>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Mes consultations programmées</h2>
            </div>

            <div class="space-y-4">
                <?php if (empty($myAppointments)): ?>
                    <p class="text-sm text-slate-400 italic">Vous n'avez aucun rendez-vous planifié.</p>
                <?php else: ?>
                    <?php foreach ($myAppointments as $app):
                        $timeslot = $app->getTimeslot();
                        $dateInfo = DateHelper::formatTimeslotDate($timeslot->getStartTime());
                        $endDateTime = new DateTime($timeslot->getEndTime());
                        $doctorUser = $app->getDoctor()->getUser();
                        ?>
                        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 transition hover:shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                            <div class="flex items-center space-x-4">
                                <?php
                                $bgDateClass = 'bg-slate-100 text-slate-600 border-slate-200';
                                if ($app->getStatus() === 'confirmed') $bgDateClass = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                                if ($app->getStatus() === 'pending') $bgDateClass = 'bg-amber-50 text-amber-600 border-amber-100';
                                ?>
                                <div class="<?= $bgDateClass ?> font-bold px-3 py-2 rounded-xl text-center min-w-[75px] border">
                                    <span class="block text-xs uppercase opacity-70 font-medium"><?= (new DateTime($timeslot->getStartTime()))->format('M') ?></span>
                                    <?= (new DateTime($timeslot->getStartTime()))->format('d') ?>
                                </div>

                                <div>
                                    <div class="flex items-center space-x-3">
                                        <h3 class="text-base font-bold text-slate-900">Dr. <?= htmlspecialchars($doctorUser->getFirstname() . ' ' . $doctorUser->getLastname()) ?></h3>

                                        <?php if ($app->getStatus() === 'confirmed'): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wide">Confirmé</span>
                                        <?php elseif ($app->getStatus() === 'pending'): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 uppercase tracking-wide">En attente</span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 uppercase tracking-wide"><?= htmlspecialchars($app->getStatus()) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-1">🕒 Horaire : <?= $dateInfo['heure'] ?> - <?= $endDateTime->format('H:i') ?> • Spécialité : <?= htmlspecialchars($app->getDoctor()->getSpeciality()->getName()) ?></p>
                                </div>
                            </div>

                            <div class="self-end sm:self-center">
                                <?php if ($app->getStatus() === 'pending' || $app->getStatus() === 'confirmed'): ?>
                                    <a href="patient-page.php?action=cancel&id=<?= $app->getId() ?>" class="text-slate-400 hover:text-rose-600 text-xs font-bold px-4 py-2 rounded-xl hover:bg-rose-50 transition border border-transparent hover:border-rose-100">
                                        Annuler la demande
                                    </a>
                                <?php elseif ($app->getStatus() === 'terminate'): ?>
                                    <button onclick="loadAndOpenPrescription(<?= $app->getId() ?>, 'Dr. <?= htmlspecialchars($doctorUser->getLastname()) ?>')" class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-sm flex items-center space-x-2">
                                        <span>📄</span>
                                        <span>Voir l'ordonnance</span>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <div id="prescriptionModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-3xl shadow-xl max-w-xl w-full overflow-hidden transform transition-all border border-slate-100 flex flex-col">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <div>
                    <h3 id="modalDoctorName" class="text-lg font-black text-slate-900">Docteur</h3>
                    <p class="text-xs text-emerald-600 font-semibold mt-0.5">Ordonnance médicale certifiée</p>
                </div>
                <button onclick="closePrescriptionModal()" class="text-slate-400 hover:text-slate-600 p-2 rounded-full hover:bg-slate-200/60 transition">✕</button>
            </div>
            <div class="p-6 space-y-4">
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Traitements prescrits :</span>
                <div class="w-full border border-slate-100 bg-slate-50/50 rounded-2xl p-5 text-sm text-slate-800 shadow-inner whitespace-pre-line font-mono leading-relaxed" id="prescriptionContent"></div>
            </div>
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button type="button" onclick="closePrescriptionModal()" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-2 rounded-xl text-xs transition">Fermer le document</button>
            </div>
        </div>
    </div>

    <script>
        function loadAndOpenPrescription(appointmentId, doctorName) {
            const modal = document.getElementById('prescriptionModal');
            const doctorNameElem = document.getElementById('modalDoctorName');
            const contentElem = document.getElementById('prescriptionContent');

            doctorNameElem.innerText = doctorName;
            contentElem.innerText = "Récupération sécurisée de votre ordonnance...";
            modal.classList.remove('hidden');

            fetch(`get-prescription.php?appointment_id=${appointmentId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        contentElem.textContent = data.description;
                    } else {
                        contentElem.innerText = data.message;
                    }
                })
                .catch(error => {
                    console.error("Error fetching prescription:", error);
                    contentElem.innerText = "Impossible de charger le document pour le moment.";
                });
        }

        function closePrescriptionModal() {
            document.getElementById('prescriptionModal').classList.add('hidden');
        }
    </script>

<?php
include_once __DIR__ . '/../layout/footer.php';
?>