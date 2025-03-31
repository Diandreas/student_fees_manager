@extends('layouts.app')

@section('content')
<div class="container mx-auto px-2 sm:px-4 py-4">
    <div class="mb-4">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <h5 class="font-bold text-primary-600">
                    <i class="fas fa-bolt mr-2"></i>Paiement rapide
                </h5>
                <a href="{{ route('payments.index') }}" class="flex items-center text-gray-600 hover:text-primary-600">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('payments.store') }}" method="POST" id="payment-form">
                    @csrf
                    <div class="mx-auto max-w-lg">
                        <!-- Étape 1: Sélection de l'étudiant -->
                        <div class="mb-4 payment-step" id="step1">
                            <div class="text-center mb-4">
                                <div class="inline-block w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center font-bold">1</div>
                                <h3 class="font-semibold text-lg mt-2">Sélectionner un étudiant</h3>
                            </div>
                            
                            <div class="relative mb-4">
                                <label for="student-selector" class="block text-sm font-medium text-gray-700 mb-1">Étudiant <span class="text-red-500">*</span></label>
                                <input type="text" id="student-selector" class="form-input w-full" placeholder="Rechercher un étudiant..." autocomplete="off">
                                <input type="hidden" id="student-id" name="student_id" required>
                                
                                <div id="student-search-results" class="absolute left-0 right-0 max-h-60 overflow-auto bg-white shadow-lg rounded-md z-10 border mt-1" style="display: none;"></div>
                            </div>
                            
                            <div class="student-info bg-gray-50 p-4 rounded-md mb-4" style="display: none;">
                                <div class="flex items-start gap-3">
                                    <div class="rounded-full h-12 w-12 bg-primary-100 text-primary-700 flex items-center justify-center text-lg font-bold" id="student-initials"></div>
                                    <div>
                                        <h4 class="font-medium" id="student-name"></h4>
                                        <p class="text-sm text-gray-500" id="student-id-display"></p>
                                        <p class="text-sm text-gray-500" id="student-field"></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="button" id="next-to-step2" class="btn-primary" disabled>
                                    Continuer <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Étape 2: Détails du paiement -->
                        <div class="mb-4 payment-step" id="step2" style="display: none;">
                            <div class="text-center mb-4">
                                <div class="inline-block w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center font-bold">2</div>
                                <h3 class="font-semibold text-lg mt-2">Montant et détails</h3>
                            </div>
                            
                            <div class="mb-4">
                                <label for="amount" class="form-label">Montant <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">F</span>
                                    </div>
                                    <input type="number" id="payment-amount" name="amount" class="form-input pl-8 w-full" placeholder="0" min="0" required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="payment_date" class="form-label">Date de paiement</label>
                                <input type="date" id="payment_date" name="payment_date" class="form-input w-full" value="{{ date('Y-m-d') }}">
                            </div>
                            
                            <div class="mb-4">
                                <label for="payment_method" class="form-label">Méthode de paiement <span class="text-red-500">*</span></label>
                                <select class="form-select w-full" id="payment-method" name="payment_method" required>
                                    <option value="cash">Espèces</option>
                                    <option value="bank">Banque</option>
                                    <option value="mobile">Mobile Money</option>
                                    <option value="other">Autre</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-textarea w-full" id="description" name="description" rows="2" placeholder="Frais de scolarité, inscription, etc."></textarea>
                            </div>
                            
                            <div class="flex justify-between">
                                <button type="button" id="back-to-step1" class="btn-outline">
                                    <i class="fas fa-arrow-left mr-2"></i> Retour
                                </button>
                                <button type="button" id="next-to-step3" class="btn-primary">
                                    Continuer <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Étape 3: Confirmation -->
                        <div class="mb-4 payment-step" id="step3" style="display: none;">
                            <div class="text-center mb-4">
                                <div class="inline-block w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center font-bold">3</div>
                                <h3 class="font-semibold text-lg mt-2">Confirmer le paiement</h3>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-md mb-4">
                                <div class="mb-2">
                                    <span class="text-gray-600">Étudiant:</span>
                                    <span class="font-medium" id="confirm-student"></span>
                                </div>
                                <div class="mb-2">
                                    <span class="text-gray-600">Montant:</span>
                                    <span class="font-medium" id="confirm-amount"></span> FCFA
                                </div>
                                <div class="mb-2">
                                    <span class="text-gray-600">Méthode:</span>
                                    <span class="font-medium" id="confirm-method"></span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Date:</span>
                                    <span class="font-medium" id="confirm-date"></span>
                                </div>
                            </div>
                            
                            <div class="flex justify-between">
                                <button type="button" id="back-to-step2" class="btn-outline">
                                    <i class="fas fa-arrow-left mr-2"></i> Retour
                                </button>
                                <button type="submit" id="payment-submit" class="btn-primary">
                                    <i class="fas fa-check mr-2"></i> Confirmer
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Indicateur de connexion -->
    <div id="connection-status" class="fixed bottom-4 left-4 p-2 rounded-full text-white text-xs hidden bg-orange-500">
        <i class="fas fa-wifi-slash mr-1"></i> Hors ligne
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables pour le formulaire par étapes
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');
        const nextToStep2 = document.getElementById('next-to-step2');
        const nextToStep3 = document.getElementById('next-to-step3');
        const backToStep1 = document.getElementById('back-to-step1');
        const backToStep2 = document.getElementById('back-to-step2');
        
        // Variables pour la recherche d'étudiants
        const studentSelector = document.getElementById('student-selector');
        const studentIdInput = document.getElementById('student-id');
        const searchResults = document.getElementById('student-search-results');
        const studentInfo = document.querySelector('.student-info');
        
        // Variables pour la confirmation
        const confirmStudent = document.getElementById('confirm-student');
        const confirmAmount = document.getElementById('confirm-amount');
        const confirmMethod = document.getElementById('confirm-method');
        const confirmDate = document.getElementById('confirm-date');
        
        // Vérifier l'état de la connexion
        checkConnectionStatus();
        window.addEventListener('online', checkConnectionStatus);
        window.addEventListener('offline', checkConnectionStatus);
        
        // Navigation par étapes
        nextToStep2.addEventListener('click', function() {
            step1.style.display = 'none';
            step2.style.display = 'block';
        });
        
        nextToStep3.addEventListener('click', function() {
            // Validation de l'étape 2
            const amount = document.getElementById('payment-amount').value;
            if (!amount || amount <= 0) {
                alert('Veuillez entrer un montant valide');
                return;
            }
            
            // Mise à jour des informations de confirmation
            confirmStudent.textContent = document.getElementById('student-name').textContent;
            confirmAmount.textContent = amount;
            confirmMethod.textContent = document.getElementById('payment-method').options[document.getElementById('payment-method').selectedIndex].text;
            confirmDate.textContent = document.getElementById('payment_date').value;
            
            step2.style.display = 'none';
            step3.style.display = 'block';
        });
        
        backToStep1.addEventListener('click', function() {
            step2.style.display = 'none';
            step1.style.display = 'block';
        });
        
        backToStep2.addEventListener('click', function() {
            step3.style.display = 'none';
            step2.style.display = 'block';
        });
        
        // Recherche d'étudiants
        studentSelector.addEventListener('input', debounce(function() {
            const query = this.value.trim();
            
            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }
            
            searchResults.style.display = 'block';
            searchResults.innerHTML = '<div class="p-2 text-center text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Recherche en cours...</div>';
            
            // Vérifier s'il y a une connexion internet
            if (!navigator.onLine) {
                // Mode hors ligne: rechercher dans les données locales
                const cachedStudents = JSON.parse(localStorage.getItem('cachedStudents') || '[]');
                const filteredStudents = cachedStudents.filter(student => 
                    student.fullName.toLowerCase().includes(query.toLowerCase()) || 
                    (student.student_id && student.student_id.toLowerCase().includes(query.toLowerCase()))
                );
                
                renderSearchResults(filteredStudents);
                return;
            }
            
            // Mode en ligne: faire une requête AJAX
            fetch(`/api/students/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    // Mettre en cache pour utilisation hors ligne
                    localStorage.setItem('cachedStudents', JSON.stringify(data));
                    renderSearchResults(data);
                })
                .catch(error => {
                    console.error('Erreur lors de la recherche:', error);
                    searchResults.innerHTML = '<div class="p-2 text-center text-red-500">Erreur lors de la recherche</div>';
                });
        }, 300));
        
        // Gérer les clics en dehors des résultats de recherche
        document.addEventListener('click', function(event) {
            if (!event.target.closest('#student-selector') && !event.target.closest('#student-search-results')) {
                searchResults.style.display = 'none';
            }
        });
        
        // Fonction pour afficher les résultats de recherche
        function renderSearchResults(students) {
            if (!students || students.length === 0) {
                searchResults.innerHTML = '<div class="p-2 text-center text-gray-500">Aucun étudiant trouvé</div>';
                return;
            }
            
            searchResults.innerHTML = '';
            
            students.forEach(student => {
                const item = document.createElement('div');
                item.className = 'p-2 hover:bg-gray-100 cursor-pointer border-b';
                item.innerHTML = `
                    <div class="font-medium">${student.fullName}</div>
                    <div class="text-sm text-gray-500">${student.student_id || ''} ${student.field ? `• ${student.field.name}` : ''}</div>
                `;
                
                item.addEventListener('click', function() {
                    studentSelector.value = student.fullName;
                    studentIdInput.value = student.id;
                    nextToStep2.disabled = false;
                    searchResults.style.display = 'none';
                    
                    // Afficher les informations de l'étudiant
                    document.getElementById('student-initials').textContent = student.fullName.charAt(0);
                    document.getElementById('student-name').textContent = student.fullName;
                    document.getElementById('student-id-display').textContent = student.student_id || '';
                    document.getElementById('student-field').textContent = student.field ? student.field.name : '';
                    studentInfo.style.display = 'block';
                });
                
                searchResults.appendChild(item);
            });
        }
        
        // Gérer la soumission du formulaire
        document.getElementById('payment-form').addEventListener('submit', function(event) {
            if (!navigator.onLine) {
                event.preventDefault();
                
                // Stocker le paiement localement pour synchronisation ultérieure
                const formData = new FormData(this);
                const payment = {
                    student_id: formData.get('student_id'),
                    amount: formData.get('amount'),
                    payment_date: formData.get('payment_date'),
                    payment_method: formData.get('payment_method'),
                    description: formData.get('description'),
                    receipt_number: 'TEMP-' + Date.now(),
                    created_at: new Date().toISOString(),
                    synced: false
                };
                
                // Ajouter aux paiements en attente
                const pendingPayments = JSON.parse(localStorage.getItem('pendingPayments') || '[]');
                pendingPayments.push(payment);
                localStorage.setItem('pendingPayments', JSON.stringify(pendingPayments));
                
                alert('Vous êtes hors ligne. Le paiement a été enregistré localement et sera synchronisé automatiquement lorsque vous serez à nouveau en ligne.');
                window.location.href = '/payments';
            }
        });
        
        // Vérifier l'état de la connexion
        function checkConnectionStatus() {
            const statusIndicator = document.getElementById('connection-status');
            
            if (navigator.onLine) {
                statusIndicator.classList.add('hidden');
            } else {
                statusIndicator.classList.remove('hidden');
            }
        }
        
        // Fonction utilitaire pour debounce
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }
    });
</script>
@endpush 