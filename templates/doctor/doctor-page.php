
<!-- 1. HEADER ÉPURÉ (STYLE CONTEMPORAIN) -->

<?php require_once"../layout/header.php" ?>
<!-- 2. CONTENU PRINCIPAL (STYLE MINIMALISTE) -->
<main class="flex-grow max-w-4xl w-full mx-auto px-4 sm:px-6 py-10">

    <!-- Entête Titre & Sélecteur -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-10">
        <div>
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest block mb-1">Activité du cabinet</span>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Agenda des consultations</h1>
        </div>

        <!-- Commutateur d'affichage minimaliste (US 2.1) -->
        <div class="bg-slate-200/60 p-1 rounded-xl flex space-x-1 self-start sm:self-center">
            <button class="bg-white text-slate-800 font-semibold px-4 py-2 text-xs rounded-lg shadow-sm">Vue Jour</button>
            <button class="text-slate-500 hover:text-slate-800 font-semibold px-4 py-2 text-xs rounded-lg transition">Semaine</button>
        </div>
    </div>

    <!-- GRILLE DE CRÉNEAUX AU DESIGN MODERNE -->
    <div class="space-y-4">

        <!-- CAS 1 : EN ATTENTE (US 2.2) -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 transition hover:shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-center space-x-4">
                <!-- Indicateur de temps épuré -->
                <div class="bg-amber-50 text-amber-600 font-bold px-3 py-2 rounded-xl text-center min-w-[75px] border border-amber-100">
                    <span class="block text-xs uppercase text-amber-500 font-medium">Début</span>
                    09:00
                </div>
                <div>
                    <div class="flex items-center space-x-3">
                        <h3 class="text-base font-bold text-slate-900">Yassine El Alami</h3>
                        <span class="h-2 w-2 rounded-full bg-amber-500 animate-pulse" title="En attente"></span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Durée du créneau : 60 min • Fin à 10:00</p>
                </div>
            </div>
            <div class="flex items-center space-x-2 self-end sm:self-center w-full sm:w-auto justify-end">
                <a href="/doctor/appointment/cancel?id=1" class="text-slate-400 hover:text-rose-600 text-xs font-bold px-4 py-2.5 rounded-xl hover:bg-rose-50 transition">
                    Refuser
                </a>
                <a href="/doctor/appointment/validate?id=1" class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-sm">
                    Confirmer le RDV
                </a>
            </div>
        </div>

        <!-- CAS 2 : CONFIRMÉ (US 2.1 & 2.3) -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 transition hover:shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-center space-x-4">
                <!-- Indicateur de temps épuré -->
                <div class="bg-emerald-50 text-emerald-600 font-bold px-3 py-2 rounded-xl text-center min-w-[75px] border border-emerald-100">
                    <span class="block text-xs uppercase text-emerald-500 font-medium">Début</span>
                    10:00
                </div>
                <div>
                    <div class="flex items-center space-x-3">
                        <h3 class="text-base font-bold text-slate-900">Sara Benjelloun</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wide">Confirmé</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Durée du créneau : 60 min • Fin à 11:00</p>
                </div>
            </div>
            <div class="self-end sm:self-center">
                <button onclick="openConsultationModal('Sara Benjelloun', '10:00 - 11:00')" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-sm shadow-emerald-600/10 flex items-center space-x-2">
                    <span>🩺</span>
                    <span>Prendre en charge</span>
                </button>
            </div>
        </div>

        <!-- CAS 3 : TERMINE (US 2.1) -->
        <div class="bg-slate-100/50 border border-slate-200/40 rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-6 opacity-70">
            <div class="flex items-center space-x-4">
                <div class="bg-slate-200/60 text-slate-500 font-bold px-3 py-2 rounded-xl text-center min-w-[75px]">
                    <span class="block text-xs uppercase text-slate-400 font-medium">Fait</span>
                    08:00
                </div>
                <div>
                    <h3 class="text-base font-semibold text-slate-700">Ahmed Mansouri</h3>
                    <p class="text-xs text-slate-400 mt-1">Consultation clôturée avec succès</p>
                </div>
            </div>
            <div class="text-xs font-bold text-slate-400 bg-slate-200/40 px-3 py-1.5 rounded-lg border border-slate-200/60 self-end sm:self-center">
                🔒 Dossier médical archivé
            </div>
        </div>

    </div>
</main>

<!-- 3. FENÊTRE MODALE PREMIUM (US 2.3) -->
<div id="consultationModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-3xl shadow-xl max-w-xl w-full overflow-hidden transform transition-all border border-slate-100 flex flex-col">

        <!-- Header Modale Minimaliste -->
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <div>
                <h3 id="modalPatientName" class="text-lg font-black text-slate-900">Nom du Patient</h3>
                <p id="modalPatientTime" class="text-xs text-emerald-600 font-semibold mt-0.5">Créneau horaire</p>
            </div>
            <button onclick="closeConsultationModal()" class="text-slate-400 hover:text-slate-600 p-2 rounded-full hover:bg-slate-200/60 transition">
                ✕
            </button>
        </div>

        <!-- Formulaire PHP -->
        <form action="/doctor/appointment/terminate" method="POST" class="p-6 space-y-5">
            <input type="hidden" name="appointment_id" value="2">

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                    Observations & Ordonnance :
                </label>
                <textarea
                    name="prescription_content"
                    rows="6"
                    required
                    class="w-full border border-slate-200 bg-slate-50/50 rounded-2xl p-4 text-sm text-slate-800 shadow-inner focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition placeholder:text-slate-400"
                    placeholder="Saisissez les recommandations thérapeutiques de manière sécurisée..."
                ></textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-2">
                <button type="button" onclick="closeConsultationModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold px-4 py-2.5 rounded-xl text-xs transition">
                    Annuler
                </button>
                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-5 py-2.5 rounded-xl text-xs transition shadow-sm">
                    Clôturer la séance
                </button>
            </div>
        </form>
    </div>
</div>



<!-- LOGIQUE MODALE -->
<script>
    function openConsultationModal(patientName, timeSlot) {
        document.getElementById('modalPatientName').innerText = patientName;
        document.getElementById('modalPatientTime').innerText = "⏱️ Consultation programmée de " + timeSlot;
        document.getElementById('consultationModal').classList.remove('hidden');
    }

    function closeConsultationModal() {
        document.getElementById('consultationModal').classList.add('hidden');
    }
    
</script>
<?php require_once"../layout/footer.php" ?>

