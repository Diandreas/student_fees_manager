<!-- resources/views/payments/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <h5 class="font-bold text-primary-600">
                    <i class="fas fa-money-bill-wave mr-2"></i>{{ session('current_school')->term('new_payment', 'Enregistrer un paiement') }}
                </h5>
                <a href="{{ route('payments.index') }}" class="flex items-center text-gray-600 hover:text-primary-600">
                    <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('payments.store') }}" method="POST" id="paymentForm">
                    @csrf
                    <div class="mb-4">
                        <label for="student_search" class="form-label">{{ session('current_school')->term('student', 'Étudiant') }} <span class="text-red-500">*</span></label>
                        <div class="flex space-x-2">
                            <!-- Champ caché pour l'ID de l'étudiant -->
                            <input type="hidden" id="student_id" name="student_id" value="{{ old('student_id', isset($selectedStudent) ? $selectedStudent->id : '') }}" required>
                            
                            <!-- Champ de recherche avec l'information de l'étudiant sélectionné -->
                            <div class="relative flex-grow">
                                <input type="text" 
                                    class="form-input w-full @error('student_id') border-red-500 @enderror" 
                                    id="student_search" 
                                    placeholder="{{ session('current_school')->term('search_student', 'Rechercher un étudiant') }}"
                                    value="{{ old('student_name', isset($selectedStudent) ? $selectedStudent->full_name . ' (' . $selectedStudent->field->name . ')' : '') }}"
                                    readonly>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-500">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                            
                            <!-- Bouton pour ouvrir le modal de recherche -->
                            <button type="button" id="openStudentSearch" class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 focus:outline-none">
                                <i class="fas fa-search mr-1"></i> {{ session('current_school')->term('search', 'Rechercher') }}
                            </button>
                        </div>
                        @error('student_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if(isset($selectedStudent))
                    <div class="card bg-blue-50 border border-blue-200 mb-6 student-info-card">
                        <div class="card-body p-4">
                            <h5 class="font-bold text-gray-700 mb-4">{{ session('current_school')->term('payment_info', 'Informations de paiement') }}</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="mb-2"><span class="font-semibold">{{ session('current_school')->term('student', 'Étudiant') }}:</span> {{ $selectedStudent->full_name }}</p>
                                    <p class="mb-2"><span class="font-semibold">{{ session('current_school')->term('field', 'Filière') }}:</span> {{ $selectedStudent->field->name }}</p>
                                    <p class="mb-2"><span class="font-semibold">{{ session('current_school')->term('campus', 'Campus') }}:</span> {{ $selectedStudent->field->campus->name }}</p>
                                </div>
                                <div>
                                    <p class="mb-2"><span class="font-semibold">{{ session('current_school')->term('total_fees', 'Frais totaux') }}:</span> {{ number_format($selectedStudent->field->fees, 0, ',', ' ') }} {{ session('current_school')->term('currency', 'FCFA') }}</p>
                                    <p class="mb-2"><span class="font-semibold">{{ session('current_school')->term('already_paid', 'Déjà payé') }}:</span> {{ number_format($selectedStudent->payments->sum('amount'), 0, ',', ' ') }} {{ session('current_school')->term('currency', 'FCFA') }}</p>
                                    <p class="{{ $selectedStudent->remainingAmount > 0 ? 'text-yellow-600' : 'text-green-600' }} mb-2">
                                        <span class="font-semibold">{{ session('current_school')->term('remaining', 'Reste à payer') }}:</span> {{ number_format($selectedStudent->remainingAmount, 0, ',', ' ') }} {{ session('current_school')->term('currency', 'FCFA') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="card bg-blue-50 border border-blue-200 mb-6 student-info-card hidden">
                        <div class="card-body p-4">
                            <h5 class="font-bold text-gray-700 mb-4">{{ session('current_school')->term('payment_info', 'Informations de paiement') }}</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="mb-2"><span class="font-semibold">{{ session('current_school')->term('student', 'Étudiant') }}:</span> <span id="student-name"></span></p>
                                    <p class="mb-2"><span class="font-semibold">{{ session('current_school')->term('field', 'Filière') }}:</span> <span id="student-field"></span></p>
                                    <p class="mb-2"><span class="font-semibold">{{ session('current_school')->term('campus', 'Campus') }}:</span> <span id="student-campus"></span></p>
                                </div>
                                <div>
                                    <p class="mb-2"><span class="font-semibold">{{ session('current_school')->term('total_fees', 'Frais totaux') }}:</span> <span id="student-fees"></span> {{ session('current_school')->term('currency', 'FCFA') }}</p>
                                    <p class="mb-2"><span class="font-semibold">{{ session('current_school')->term('already_paid', 'Déjà payé') }}:</span> <span id="student-paid"></span> {{ session('current_school')->term('currency', 'FCFA') }}</p>
                                    <p id="remaining-amount-text" class="mb-2">
                                        <span class="font-semibold">{{ session('current_school')->term('remaining', 'Reste à payer') }}:</span> <span id="student-remaining"></span> {{ session('current_school')->term('currency', 'FCFA') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div id="paymentInfo" class="bg-blue-100 text-blue-800 p-4 rounded mb-6 hidden"></div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-4">
                            <label for="amount" class="form-label">{{ session('current_school')->term('amount', 'Montant') }} <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" step="0.01"
                                    class="form-input @error('amount') border-red-500 @enderror"
                                    id="amount" name="amount" value="{{ old('amount') }}" required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">{{ session('current_school')->term('currency', 'FCFA') }}</span>
                                </div>
                            </div>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="payment_date" class="form-label">{{ session('current_school')->term('payment_date', 'Date de paiement') }} <span class="text-red-500">*</span></label>
                            <input type="date" class="form-input @error('payment_date') border-red-500 @enderror"
                                id="payment_date" name="payment_date"
                                value="{{ old('payment_date', date('Y-m-d')) }}" required>
                            @error('payment_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="payment_method" class="form-label">{{ session('current_school')->term('payment_method', 'Méthode de paiement') }} <span class="text-red-500">*</span></label>
                        <select class="form-select @error('payment_method') border-red-500 @enderror" id="payment_method" name="payment_method" required>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>{{ session('current_school')->term('cash', 'Espèces') }}</option>
                            <option value="bank" {{ old('payment_method') == 'bank' ? 'selected' : '' }}>{{ session('current_school')->term('bank', 'Banque') }}</option>
                            <option value="mobile" {{ old('payment_method') == 'mobile' ? 'selected' : '' }}>{{ session('current_school')->term('mobile_money', 'Mobile Money') }}</option>
                            <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>{{ session('current_school')->term('other', 'Autre') }}</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">{{ session('current_school')->term('description', 'Description') }}</label>
                        <textarea class="form-textarea @error('description') border-red-500 @enderror"
                                id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('payments.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                            {{ session('current_school')->term('cancel', 'Annuler') }}
                        </a>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i>{{ session('current_school')->term('save', 'Enregistrer') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de recherche d'étudiants -->
<div id="studentSearchModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[90vh] flex flex-col">
        <div class="p-4 border-b flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-search mr-2"></i>{{ session('current_school')->term('search_student', 'Rechercher un étudiant') }}
            </h3>
            <button type="button" id="closeStudentSearchModal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="p-4 border-b">
            <div class="relative">
                <input type="text" 
                    id="modalStudentSearch" 
                    class="form-input w-full pr-10" 
                    placeholder="{{ session('current_school')->term('search_by_name_or_field', 'Rechercher par nom, prénom ou filière...') }}"
                    autocomplete="off">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </div>
        
        <div class="p-4 overflow-y-auto flex-grow">
            <div id="searchMessage" class="text-center text-gray-500 py-4">
                {{ session('current_school')->term('start_typing', 'Commencez à taper pour rechercher un étudiant...') }}
            </div>
            <div id="studentList" class="divide-y">
                <!-- Les résultats de recherche seront ajoutés ici dynamiquement -->
            </div>
            <div id="loadingIndicator" class="text-center py-4 hidden">
                <i class="fas fa-spinner fa-spin mr-2"></i> {{ session('current_school')->term('loading', 'Chargement...') }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Éléments du DOM
            const studentIdInput = document.getElementById('student_id');
            const studentSearchInput = document.getElementById('student_search');
            const openStudentSearchButton = document.getElementById('openStudentSearch');
            const studentSearchModal = document.getElementById('studentSearchModal');
            const closeStudentSearchModalButton = document.getElementById('closeStudentSearchModal');
            const modalStudentSearchInput = document.getElementById('modalStudentSearch');
            const studentList = document.getElementById('studentList');
            const searchMessage = document.getElementById('searchMessage');
            const loadingIndicator = document.getElementById('loadingIndicator');
            const studentInfoCard = document.querySelector('.student-info-card');
            
            // Fonction pour formater des nombres avec des séparateurs
            function formatNumber(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
            }
            
            // Fonction pour mettre à jour l'affichage du montant maximum
            function updateAmountMax(remainingAmount) {
                const amountInput = document.getElementById('amount');
                if (amountInput) {
                    amountInput.setAttribute('max', remainingAmount);
                    
                    // Si le montant actuel est supérieur au restant, le mettre à jour
                    if (parseFloat(amountInput.value) > remainingAmount) {
                        amountInput.value = remainingAmount;
                    }
                }
            }
            
            // Fonction pour charger les informations de l'étudiant
            function loadStudentInfo(studentId) {
                if (!studentId) return;
                
                loadingIndicator.classList.remove('hidden');
                fetch(`{{ url('/payments/student-remaining') }}/${studentId}`)
                    .then(response => response.json())
                    .then(data => {
                        loadingIndicator.classList.add('hidden');
                        
                        // Vérifier que data contient les informations attendues
                        if (!data || !data.student) {
                            console.error('Les données de l\'étudiant sont incomplètes:', data);
                            return;
                        }
                        
                        // Afficher la carte d'information
                        studentInfoCard.classList.remove('hidden');
                        
                        // Vérifier et définir les valeurs avec des valeurs par défaut pour éviter undefined
                        const studentName = data.student.full_name || data.student.fullName || 'Non disponible';
                        const fieldName = data.student.field && data.student.field.name ? data.student.field.name : 'Non disponible';
                        const campusName = data.student.field && data.student.field.campus && data.student.field.campus.name ? data.student.field.campus.name : 'Non disponible';
                        const totalFees = data.totalFees || 0;
                        const totalPaid = data.totalPaid || 0;
                        const remainingAmount = data.remainingAmount || 0;
                        
                        // Mettre à jour les informations
                        document.getElementById('student-name').textContent = studentName;
                        document.getElementById('student-field').textContent = fieldName;
                        document.getElementById('student-campus').textContent = campusName;
                        document.getElementById('student-fees').textContent = formatNumber(totalFees);
                        document.getElementById('student-paid').textContent = formatNumber(totalPaid);
                        document.getElementById('student-remaining').textContent = formatNumber(remainingAmount);
                        
                        // Mettre à jour la classe pour la couleur du texte
                        const remainingText = document.getElementById('remaining-amount-text');
                        if (remainingAmount > 0) {
                            remainingText.className = 'text-yellow-600 mb-2';
                        } else {
                            remainingText.className = 'text-green-600 mb-2';
                        }
                        
                        // Mettre à jour le montant maximum
                        updateAmountMax(remainingAmount);
                        
                        // Désactiver le bouton de soumission si la pension est déjà soldée
                        const submitButton = document.querySelector('button[type="submit"]');
                        const paymentInfo = document.getElementById('paymentInfo');
                        if (remainingAmount <= 0) {
                            submitButton.disabled = true;
                            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                            paymentInfo.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i>La pension de cet étudiant est déjà soldée. Impossible d\'effectuer un paiement.';
                            paymentInfo.classList.remove('hidden', 'bg-blue-100', 'text-blue-800');
                            paymentInfo.classList.add('bg-red-100', 'text-red-800');
                            document.getElementById('amount').disabled = true;
                        } else {
                            submitButton.disabled = false;
                            submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                            paymentInfo.innerHTML = `<i class="fas fa-info-circle mr-2"></i>Reste à payer : ${formatNumber(remainingAmount)} {{ session('current_school')->term('currency', 'FCFA') }}`;
                            paymentInfo.classList.remove('hidden', 'bg-red-100', 'text-red-800');
                            paymentInfo.classList.add('bg-blue-100', 'text-blue-800');
                            document.getElementById('amount').disabled = false;
                        }
                        
                        // Afficher le message d'information de paiement
                        paymentInfo.classList.remove('hidden');
                    })
                    .catch(error => {
                        loadingIndicator.classList.add('hidden');
                        console.error('Error:', error);
                    });
            }
            
            // Fonction pour rechercher des étudiants
            let searchTimeout = null;
            function searchStudents(query) {
                // Annuler la recherche précédente si elle existe
                if (searchTimeout) {
                    clearTimeout(searchTimeout);
                }
                
                // Ne rechercher que si la requête a au moins 2 caractères
                if (query.length < 2) {
                    searchMessage.textContent = "{{ session('current_school')->term('type_more', 'Tapez au moins 2 caractères pour lancer la recherche...') }}";
                    searchMessage.classList.remove('hidden');
                    studentList.innerHTML = '';
                    return;
                }
                
                // Afficher le chargement
                loadingIndicator.classList.remove('hidden');
                searchMessage.classList.add('hidden');
                
                // Attendre un peu avant de lancer la recherche pour éviter les requêtes trop fréquentes
                searchTimeout = setTimeout(() => {
                    fetch(`{{ url('/api/students/search') }}?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            loadingIndicator.classList.add('hidden');
                            
                            if (!data || data.length === 0) {
                                searchMessage.textContent = "{{ session('current_school')->term('no_results', 'Aucun résultat trouvé') }}";
                                searchMessage.classList.remove('hidden');
                                studentList.innerHTML = '';
                                return;
                            }
                            
                            // Afficher les résultats
                            searchMessage.classList.add('hidden');
                            studentList.innerHTML = '';
                            
                            data.forEach(student => {
                                // Vérification des propriétés pour éviter les problèmes d'undefined
                                const studentName = student.full_name || student.fullName || 'Nom non disponible';
                                const fieldName = student.field && student.field.name ? student.field.name : 'Filière non disponible';
                                const campusName = student.field && student.field.campus && student.field.campus.name ? student.field.campus.name : 'Campus non disponible';
                                const fees = student.field && student.field.fees ? student.field.fees : 0;
                                const remainingAmount = student.remaining_amount || 0;
                                
                                // Créer l'élément de la liste
                                const studentItem = document.createElement('div');
                                studentItem.className = 'p-3 hover:bg-gray-100 cursor-pointer flex items-start';
                                studentItem.setAttribute('data-student-id', student.id);
                                studentItem.setAttribute('data-student-name', studentName);
                                studentItem.setAttribute('data-field-name', fieldName);
                                
                                // Statut de paiement (rond coloré)
                                let statusColor;
                                if (student.payment_status === 'fully_paid') {
                                    statusColor = 'bg-green-500';
                                } else if (student.payment_status === 'partially_paid') {
                                    statusColor = 'bg-yellow-500';
                                } else {
                                    statusColor = 'bg-red-500';
                                }
                                
                                // Contenu HTML
                                studentItem.innerHTML = `
                                    <div class="mr-3 mt-1">
                                        <div class="w-3 h-3 ${statusColor} rounded-full"></div>
                                    </div>
                                    <div class="flex-grow">
                                        <div class="font-medium">${studentName}</div>
                                        <div class="text-sm text-gray-600">${fieldName} - ${campusName}</div>
                                        <div class="mt-1 text-sm">
                                            <span class="font-semibold">Total:</span> ${formatNumber(fees)} {{ session('current_school')->term('currency', 'FCFA') }} | 
                                            <span class="font-semibold">Reste:</span> <span class="${remainingAmount > 0 ? 'text-yellow-600' : 'text-green-600'}">${formatNumber(remainingAmount)} {{ session('current_school')->term('currency', 'FCFA') }}</span>
                                        </div>
                                    </div>
                                `;
                                
                                // Événement click pour sélectionner l'étudiant
                                studentItem.addEventListener('click', () => {
                                    selectStudent(student.id, `${studentName} (${fieldName})`);
                                    closeModal();
                                });
                                
                                // Ajouter à la liste
                                studentList.appendChild(studentItem);
                            });
                        })
                        .catch(error => {
                            loadingIndicator.classList.add('hidden');
                            searchMessage.textContent = "{{ session('current_school')->term('error', 'Une erreur est survenue, veuillez réessayer') }}";
                            searchMessage.classList.remove('hidden');
                            console.error('Error:', error);
                        });
                }, 300);
            }
            
            // Fonction pour sélectionner un étudiant
            function selectStudent(id, displayName) {
                studentIdInput.value = id;
                studentSearchInput.value = displayName;
                
                // Charger les informations de l'étudiant
                loadStudentInfo(id);
            }
            
            // Fonctions pour le modal
            function openModal() {
                studentSearchModal.classList.remove('hidden');
                modalStudentSearchInput.focus();
                
                // Réinitialiser la recherche
                modalStudentSearchInput.value = '';
                searchMessage.textContent = "{{ session('current_school')->term('start_typing', 'Commencez à taper pour rechercher un étudiant...') }}";
                searchMessage.classList.remove('hidden');
                studentList.innerHTML = '';
            }
            
            function closeModal() {
                studentSearchModal.classList.add('hidden');
            }
            
            // Événements
            openStudentSearchButton.addEventListener('click', openModal);
            closeStudentSearchModalButton.addEventListener('click', closeModal);
            
            modalStudentSearchInput.addEventListener('input', function() {
                searchStudents(this.value.trim());
            });
            
            // Fermer le modal en cliquant en dehors
            studentSearchModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });
            
            // Ouvrir le modal en cliquant sur le champ de recherche
            studentSearchInput.addEventListener('click', openModal);
            
            // Charger les informations de l'étudiant si déjà sélectionné
            if (studentIdInput.value) {
                loadStudentInfo(studentIdInput.value);
            }
            
            // Gérer la soumission du formulaire
            document.getElementById('paymentForm').addEventListener('submit', function(e) {
                if (!studentIdInput.value) {
                    e.preventDefault();
                    alert("{{ session('current_school')->term('select_student_first', 'Veuillez sélectionner un étudiant avant de soumettre le formulaire') }}");
                }
            });
        });
    </script>
@endpush
@endsection
