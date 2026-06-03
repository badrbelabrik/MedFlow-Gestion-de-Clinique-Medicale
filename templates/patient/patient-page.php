<?php
$pageTitle = "Espace Patient — MedFlow";
include_once __DIR__ . '/../layout/header.php';

use App\Helpers\DateHelper;

// Note : Ces variables ($doctors et $myAppointments) doivent être préparées
// dans ton contrôleur et transmises à cette vue.
// Si elles ne sont pas définies, on initialise des tableaux vides pour éviter les crashs.
$doctors = $doctors ?? [];
$myAppointments = $myAppointments ?? [];
?>

    <main class="flex-grow max-w-4xl w-full mx-auto px-4 sm:px-6 py-10 space-y-12">

        <section class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-8">
            <div>
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest block mb-1">Prise de rendez-vous en ligne</span>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Rechercher un médecin</h2>
                <p class="text-xs text-slate-400 mt-1">Filtrez par spécialité ou par nom pour afficher les disponibilités en temps réel.</p>
            </div>

            <form action="/patient/dashboard" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
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
                        <option value="1" <?= ($_GET['speciality_id'] ?? '') == '1' ? 'selected' : '' ?>>Médecine Générale</option>
                        <option value="2" <?= ($_GET['speciality_id'] ?? '') == '2' ? 'selected' : '' ?>>Cardiologie</option>
                        <option value="3" <?= ($_GET['speciality_id'] ?? '') == '3' ? 'selected' : '' ?>>Pédiatrie</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold py-2.5 rounded-xl transition shadow-sm h-[44px]">
                        Rechercher
                    </button>
                </div>
            </form>

            <?php if (isset($_GET['firstname']) || isset($_GET['lastname']) || isset($_GET['speciality_id'])): ?>
                <div class="space-y-6">
                    <?php if (empty($doctors)): ?>
                        <div class="text-center py-8 border border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                            <span class="text-2xl">🔍</span>
                            <p class="text-sm font-medium text-slate-500 mt-2">Aucun médecin ne correspond à vos critères de recherche.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($doctors as $doctor):
                            // On suppose que ton modèle Doctor a ces méthodes via sa composition
                            $docUser = $doctor->getUser();
                            $docSpec = $doctor->getSpeciality();

                            // Récupération des créneaux via le contrôleur (injectés dans l'objet ou via un tableau groupé)
                            // Exemple ici : on imagine que le contrôleur a rattaché les créneaux libres au médecin
                            $timeslots = $doctor->getAvailableTimeslots() ?? [];
                            ?>
                            <div class="border border-slate-200/60 rounded-2xl p-5 bg-white space-y-4 shadow-sm hover:border-slate-300/80 transition">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-4 gap-2">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                                            Dr
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-slate-900">
                                                Dr. <?= htmlspecialchars($docUser->getFirstname() . ' ' . $docUser->getLastname()) ?>
                                            </h4>
                                            <p class="text-xs text-slate-400">
                                                <?= htmlspecialchars($docSpec->getName()) ?> — Cabinet Professionnel
                                            </p>
                                        </div>
                                    </div>
                                    <span class="text-[11px] font-medium text-slate-400 bg-slate-100 px-2.5 py-1 rounded-md">
                                    Disponibilités en temps réel
                                </span>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">Sélectionnez un créneau horaire libre :</label>

                                    <form action="/patient/appointment/book" method="POST">
                                        <input type="hidden" name="id_doctor" value="<?= $doctor->getId() ?>">

                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                            <?php if (empty($timeslots)): ?>
                                                <p class="text-xs text-slate-400 italic col-span-3 py-2">Aucun créneau disponible pour le moment.</p>
                                            <?php else: ?>
                                                <?php foreach ($timeslots as $slot):
                                                    $start = DateHelper::formatTimeslotDate($slot['start_time']);
                                                    $end = new DateTime($slot['end_time']);
                                                    ?>
                                                    <label class="relative border border-slate-200 rounded-xl p-3 flex flex-col items-center justify-center cursor-pointer hover:bg-emerald-50/30 hover:border-emerald-500 transition group has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/20">
                                                        <input type="radio" name="id_timeslot" value="<?= $slot['id'] ?>" required class="absolute top-2 right-2 h-4 w-4 text-emerald-600 focus:ring-emerald-500 accent-emerald-600">
                                                        <span class="block text-xs font-bold text-slate-700 group-hover:text-emerald-900"><?= $start['date_texte'] ?></span>
                                                        <span class="block text-[11px] font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded mt-1.5 group-hover:bg-emerald-100 group-hover:text-emerald-800"><?= $start['heure'] ?> - <?= $end->format('H:i') ?></span>
                                                    </label>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (!empty($timeslots)): ?>
                                            <div class="mt-4 pt-2 flex justify-end">
                                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition shadow-sm flex items-center space-x-2">
                                                    <span>✨</span>
                                                    <span>Demander ce rendez-vous</span>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
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
                                <?php if ($app->getStatus() === 'pending'): ?>
                                    <a href="/patient/appointment/cancel?id=<?= $app->getId() ?>" class="text-slate-400 hover:text-rose-600 text-xs font-bold px-4 py-2 rounded-xl hover:bg-rose-50 transition border border-transparent hover:border-rose-100">
                                        Annuler la demande
                                    </a>
                                <?php elseif ($app->getStatus() === 'terminate'): ?>
                                    <button onclick="openPrescriptionModal('Dr. <?= htmlspecialchars($doctorUser->getLastname()) ?>', 'Détail de l\'ordonnance archivée.')" class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-sm flex items-center space-x-2">
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
        function openPrescriptionModal(doctorName, treatmentText) {
            document.getElementById('modalDoctorName').innerText = doctorName;
            document.getElementById('prescriptionContent').innerText = treatmentText;
            document.getElementById('prescriptionModal').classList.remove('hidden');
        }

        function closePrescriptionModal() {
            document.getElementById('prescriptionModal').classList.add('hidden');
        }
    </script>

<?php
include_once __DIR__ . '/../layout/footer.php';
?>