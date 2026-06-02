<?php
$pageTitle = "Espace Patient — MedFlow";
include_once __DIR__ . '/../layout/header.php';
?>

<!-- 2. CONTENU PRINCIPAL -->
<main class="flex-grow max-w-4xl w-full mx-auto px-4 sm:px-6 py-10 space-y-12">

    <!-- SECTION A : PRENDRE RENDEZ-VOUS (Action Principale) -->
    <section class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm">
        <div class="mb-6">
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest block mb-1">Prise de rendez-vous en ligne</span>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Trouver un praticien disponible</h2>
        </div>

        <!-- Formulaire de réservation -->
        <form action="/patient/appointment/book" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <!-- 1. Sélection de la spécialité -->
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">1. Spécialité médicale</label>
                <select name="speciality_id" class="w-full border border-slate-200 bg-slate-50 rounded-xl p-3 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    <option value="">Choisir une spécialité...</option>
                    <option value="1">Cardiologie</option>
                    <option value="2">Médecine Générale</option>
                    <option value="3">Pédiatrie</option>
                </select>
            </div>

            <!-- 2. Sélection du créneau disponible (Généré dynamiquement en PHP) -->
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">2. Médecin & Créneau libre</label>
                <select name="timeslot_id" class="w-full border border-slate-200 bg-slate-50 rounded-xl p-3 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    <option value="">Sélectionner un horaire...</option>
                    <option value="10">Dr. K. Bennani — Mar. 2 Juin (14:00 - 15:00)</option>
                    <option value="11">Dr. K. Bennani — Mer. 3 Juin (11:00 - 12:00)</option>
                    <option value="12">Dr. A. Merini — Jeu. 4 Juin (09:30 - 10:30)</option>
                </select>
            </div>

            <!-- 3. Bouton de soumission -->
            <div>
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold p-3 rounded-xl transition shadow-sm shadow-emerald-600/10 flex items-center justify-center space-x-2">
                    <span>✨</span>
                    <span>Confirmer la demande</span>
                </button>
            </div>
        </form>
    </section>

    <!-- SECTION B : SUIVI DES RENDEZ-VOUS (Historique & Statuts) -->
    <section class="space-y-4">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Suivi en temps réel</span>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Mes consultations programmées</h2>
        </div>

        <!-- Liste des rendez-vous du patient -->
        <div class="space-y-4">

            <!-- ÉTAT 1 : CONFIRMÉ (Bientôt disponible) -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 transition hover:shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div class="flex items-center space-x-4">
                    <div class="bg-emerald-50 text-emerald-600 font-bold px-3 py-2 rounded-xl text-center min-w-[75px] border border-emerald-100">
                        <span class="block text-xs uppercase text-emerald-500 font-medium">Juin</span>
                        02
                    </div>
                    <div>
                        <div class="flex items-center space-x-3">
                            <h3 class="text-base font-bold text-slate-900">Dr. Karim Bennani</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wide">Confirmé</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">🕒 Horaire : 10:00 - 11:00 • Spécialité : Cardiologie</p>
                    </div>
                </div>
                <div class="text-xs font-semibold text-slate-400 italic self-end sm:self-center">
                    Présentez-vous au cabinet à l'heure indiquée
                </div>
            </div>

            <!-- ÉTAT 2 : EN ATTENTE DE VALIDATION -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 transition hover:shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div class="flex items-center space-x-4">
                    <div class="bg-amber-50 text-amber-600 font-bold px-3 py-2 rounded-xl text-center min-w-[75px] border border-amber-100">
                        <span class="block text-xs uppercase text-amber-500 font-medium">Juin</span>
                        05
                    </div>
                    <div>
                        <div class="flex items-center space-x-3">
                            <h3 class="text-base font-bold text-slate-900">Dr. Amina Merini</h3>
                            <span class="h-2 w-2 rounded-full bg-amber-500 animate-pulse" title="En attente du médecin"></span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">🕒 Horaire : 14:30 - 15:30 • Spécialité : Médecine Générale</p>
                    </div>
                </div>
                <div class="self-end sm:self-center">
                    <a href="/patient/appointment/cancel?id=4" class="text-slate-400 hover:text-rose-600 text-xs font-bold px-4 py-2 rounded-xl hover:bg-rose-50 transition border border-transparent hover:border-rose-100">
                        Annuler la demande
                    </a>
                </div>
            </div>

            <!-- ÉTAT 3 : TERMINÉ AVEC ACCÈS À L'ORDONNANCE -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 transition hover:shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div class="flex items-center space-x-4">
                    <div class="bg-slate-100 text-slate-500 font-bold px-3 py-2 rounded-xl text-center min-w-[75px] border border-slate-200/60">
                        <span class="block text-xs uppercase text-slate-400 font-medium">Mai</span>
                        24
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-700">Dr. Karim Bennani</h3>
                        <p class="text-xs text-slate-400 mt-1">Consultation passée • Ordonnance disponible</p>
                    </div>
                </div>
                <div class="self-end sm:self-center">
                    <!-- Ouvre la modale pour voir l'ordonnance textuelle rédigée par le médecin -->
                    <button onclick="openPrescriptionModal('Dr. Karim Bennani', 'Paracétamol 1g : 1 comprimé 3 fois par jour pendant 5 jours.\nRepos strict de 48 heures.')" class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-sm flex items-center space-x-2">
                        <span>📄</span>
                        <span>Voir l'ordonnance</span>
                    </button>
                </div>
            </div>

        </div>
    </section>
</main>

<!-- 3. FENÊTRE MODALE : VUE DE L'ORDONNANCE (Lecture Seule) -->
<div id="prescriptionModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-3xl shadow-xl max-w-xl w-full overflow-hidden transform transition-all border border-slate-100 flex flex-col">

        <!-- Entête Modale -->
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <div>
                <h3 id="modalDoctorName" class="text-lg font-black text-slate-900">Docteur</h3>
                <p class="text-xs text-emerald-600 font-semibold mt-0.5">Ordonnance médicale certifiée</p>
            </div>
            <button onclick="closePrescriptionModal()" class="text-slate-400 hover:text-slate-600 p-2 rounded-full hover:bg-slate-200/60 transition">
                ✕
            </button>
        </div>

        <!-- Corps de l'ordonnance (Lecture seule) -->
        <div class="p-6 space-y-4">
            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Traitements prescrits :</span>

            <!-- Zone d'affichage du texte -->
            <div class="w-full border border-slate-100 bg-slate-50/50 rounded-2xl p-5 text-sm text-slate-800 shadow-inner whitespace-pre-line font-mono leading-relaxed" id="prescriptionContent">
                Texte de l'ordonnance...
            </div>
        </div>

        <!-- Pied de la modale -->
        <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end">
            <button type="button" onclick="closePrescriptionModal()" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-2 rounded-xl text-xs transition">
                Fermer le document
            </button>
        </div>
    </div>
</div>
<!-- LOGIQUE MODALE PATIENT -->
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
// 3. Inclure le Footer
include_once __DIR__ . '/../layout/footer.php';
?>