<?php
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

        <form action="/patient/dashboard" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Firstname :</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">🔍</span>
                    <input type="text" name="firstname" placeholder="Ex: Bennani..." class="w-full border border-slate-200 bg-white rounded-xl pl-8 pr-3 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 transition">
                </div>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Lastname :</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">🔍</span>
                    <input type="text" name="lastname" placeholder="Ex: Bennani..." class="w-full border border-slate-200 bg-white rounded-xl pl-8 pr-3 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 transition">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Spécialité médicale</label>
                <select name="speciality_id" class="w-full border border-slate-200 bg-white rounded-xl p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 transition">
                    <option value="">Toutes les spécialités</option>
                    <option value="1">Médecine Générale</option>
                    <option value="2">Cardiologie</option>
                    <option value="3">Pédiatrie</option>
                </select>
            </div>

            <div>
                <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold py-2.5 rounded-xl transition shadow-sm h-[42px]">
                    Rechercher
                </button>
            </div>
        </form>

        <div class="border border-slate-100 rounded-2xl p-5 bg-white space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-4 gap-2">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                        Dr
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">Dr. Karim Bennani</h4>
                        <p class="text-xs text-slate-400">Cardiologue — Cabinet N°4</p>
                    </div>
                </div>
                <span class="text-[11px] font-medium text-slate-400 bg-slate-100 px-2.5 py-1 rounded-md">
                    Prochaines disponibilités
                </span>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">Sélectionnez un créneau horaire libre :</label>

                <form action="/patient/appointment/book" method="POST">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">

                        <label class="relative border border-slate-200 rounded-xl p-3 flex flex-col items-center justify-center cursor-pointer hover:bg-emerald-50/30 hover:border-emerald-500 transition group">
                            <input type="radio" name="timeslot_id" value="10" class="absolute top-2 right-2 accent-emerald-600">
                            <span class="block text-xs font-bold text-slate-700 group-hover:text-emerald-900">Mar. 2 Juin</span>
                            <span class="block text-[11px] font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded mt-1.5 group-hover:bg-emerald-100 group-hover:text-emerald-800">14:00 - 15:00</span>
                        </label>

                        <label class="relative border border-slate-200 rounded-xl p-3 flex flex-col items-center justify-center cursor-pointer hover:bg-emerald-50/30 hover:border-emerald-500 transition group">
                            <input type="radio" name="timeslot_id" value="11" class="absolute top-2 right-2 accent-emerald-600">
                            <span class="block text-xs font-bold text-slate-700 group-hover:text-emerald-900">Mer. 3 Juin</span>
                            <span class="block text-[11px] font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded mt-1.5 group-hover:bg-emerald-100 group-hover:text-emerald-800">11:00 - 12:00</span>
                        </label>

                        <label class="relative border border-slate-200 rounded-xl p-3 flex flex-col items-center justify-center cursor-pointer hover:bg-emerald-50/30 hover:border-emerald-500 transition group">
                            <input type="radio" name="timeslot_id" value="12" class="absolute top-2 right-2 accent-emerald-600">
                            <span class="block text-xs font-bold text-slate-700 group-hover:text-emerald-900">Jeu. 4 Juin</span>
                            <span class="block text-[11px] font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded mt-1.5 group-hover:bg-emerald-100 group-hover:text-emerald-800">09:30 - 10:30</span>
                        </label>

                    </div>

                    <div class="mt-4 pt-2 flex justify-end">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition shadow-sm flex items-center space-x-2">
                            <span>✨</span>
                            <span>Demander ce rendez-vous</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="space-y-4">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Suivi en temps réel</span>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Mes consultations programmées</h2>
        </div>

        <div class="space-y-4">
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
                    <button onclick="openPrescriptionModal('Dr. Karim Bennani', 'Paracétamol 1g : 1 comprimé 3 fois par jour pendant 5 jours.\nRepos strict de 48 heures.')" class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-sm flex items-center space-x-2">
                        <span>📄</span>
                        <span>Voir l'ordonnance</span>
                    </button>
                </div>
            </div>
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