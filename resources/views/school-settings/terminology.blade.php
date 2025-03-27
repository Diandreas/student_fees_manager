@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar des paramètres -->
        @include('school-settings.partials.sidebar')
        
        <!-- Contenu principal -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2 text-primary-custom">Personnalisation de la terminologie</h1>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary-custom">
                        Termes personnalisés
                    </h5>
                    <p class="text-muted mb-0">
                        Adaptez les termes utilisés dans l'application selon votre contexte éducatif
                    </p>
                </div>
                <div class="card-body">
                    <form action="{{ route('school.settings.terminology.update', $school) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3">Entités principales</h6>
                                
                                <div class="mb-3">
                                    <label for="terminology_student" class="form-label">Étudiant (singulier)</label>
                                    <input type="text" class="form-control" id="terminology_student" 
                                        name="terminology[student]" 
                                        value="{{ $school->terminology['student'] ?? $defaultTerms['student'] }}"
                                        placeholder="{{ $defaultTerms['student'] }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="terminology_students" class="form-label">Étudiants (pluriel)</label>
                                    <input type="text" class="form-control" id="terminology_students" 
                                        name="terminology[students]" 
                                        value="{{ $school->terminology['students'] ?? $defaultTerms['students'] }}"
                                        placeholder="{{ $defaultTerms['students'] }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="terminology_field" class="form-label">Filière (singulier)</label>
                                    <input type="text" class="form-control" id="terminology_field" 
                                        name="terminology[field]" 
                                        value="{{ $school->terminology['field'] ?? $defaultTerms['field'] }}"
                                        placeholder="{{ $defaultTerms['field'] }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="terminology_fields" class="form-label">Filières (pluriel)</label>
                                    <input type="text" class="form-control" id="terminology_fields" 
                                        name="terminology[fields]" 
                                        value="{{ $school->terminology['fields'] ?? $defaultTerms['fields'] }}"
                                        placeholder="{{ $defaultTerms['fields'] }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="terminology_campus" class="form-label">Campus (singulier)</label>
                                    <input type="text" class="form-control" id="terminology_campus" 
                                        name="terminology[campus]" 
                                        value="{{ $school->terminology['campus'] ?? $defaultTerms['campus'] }}"
                                        placeholder="{{ $defaultTerms['campus'] }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="terminology_campuses" class="form-label">Campus (pluriel)</label>
                                    <input type="text" class="form-control" id="terminology_campuses" 
                                        name="terminology[campuses]" 
                                        value="{{ $school->terminology['campuses'] ?? $defaultTerms['campuses'] }}"
                                        placeholder="{{ $defaultTerms['campuses'] }}">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3">Finances et cours</h6>
                                
                                <div class="mb-3">
                                    <label for="terminology_payment" class="form-label">Paiement (singulier)</label>
                                    <input type="text" class="form-control" id="terminology_payment" 
                                        name="terminology[payment]" 
                                        value="{{ $school->terminology['payment'] ?? $defaultTerms['payment'] }}"
                                        placeholder="{{ $defaultTerms['payment'] }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="terminology_payments" class="form-label">Paiements (pluriel)</label>
                                    <input type="text" class="form-control" id="terminology_payments" 
                                        name="terminology[payments]" 
                                        value="{{ $school->terminology['payments'] ?? $defaultTerms['payments'] }}"
                                        placeholder="{{ $defaultTerms['payments'] }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="terminology_class" class="form-label">Classe (singulier)</label>
                                    <input type="text" class="form-control" id="terminology_class" 
                                        name="terminology[class]" 
                                        value="{{ $school->terminology['class'] ?? $defaultTerms['class'] }}"
                                        placeholder="{{ $defaultTerms['class'] }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="terminology_classes" class="form-label">Classes (pluriel)</label>
                                    <input type="text" class="form-control" id="terminology_classes" 
                                        name="terminology[classes]" 
                                        value="{{ $school->terminology['classes'] ?? $defaultTerms['classes'] }}"
                                        placeholder="{{ $defaultTerms['classes'] }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="terminology_fee" class="form-label">Frais (singulier)</label>
                                    <input type="text" class="form-control" id="terminology_fee" 
                                        name="terminology[fee]" 
                                        value="{{ $school->terminology['fee'] ?? $defaultTerms['fee'] }}"
                                        placeholder="{{ $defaultTerms['fee'] }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="terminology_fees" class="form-label">Frais (pluriel)</label>
                                    <input type="text" class="form-control" id="terminology_fees" 
                                        name="terminology[fees]" 
                                        value="{{ $school->terminology['fees'] ?? $defaultTerms['fees'] }}"
                                        placeholder="{{ $defaultTerms['fees'] }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3">Personnes</h6>
                                
                                <div class="mb-3">
                                    <label for="terminology_teacher" class="form-label">Enseignant (singulier)</label>
                                    <input type="text" class="form-control" id="terminology_teacher" 
                                        name="terminology[teacher]" 
                                        value="{{ $school->terminology['teacher'] ?? $defaultTerms['teacher'] }}"
                                        placeholder="{{ $defaultTerms['teacher'] }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="terminology_teachers" class="form-label">Enseignants (pluriel)</label>
                                    <input type="text" class="form-control" id="terminology_teachers" 
                                        name="terminology[teachers]" 
                                        value="{{ $school->terminology['teachers'] ?? $defaultTerms['teachers'] }}"
                                        placeholder="{{ $defaultTerms['teachers'] }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="terminology_parent" class="form-label">Parent (singulier)</label>
                                    <input type="text" class="form-control" id="terminology_parent" 
                                        name="terminology[parent]" 
                                        value="{{ $school->terminology['parent'] ?? $defaultTerms['parent'] }}"
                                        placeholder="{{ $defaultTerms['parent'] }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="terminology_parents" class="form-label">Parents (pluriel)</label>
                                    <input type="text" class="form-control" id="terminology_parents" 
                                        name="terminology[parents]" 
                                        value="{{ $school->terminology['parents'] ?? $defaultTerms['parents'] }}"
                                        placeholder="{{ $defaultTerms['parents'] }}">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3">Interface</h6>
                                
                                <div class="mb-3">
                                    <label for="terminology_dashboard" class="form-label">Tableau de bord</label>
                                    <input type="text" class="form-control" id="terminology_dashboard" 
                                        name="terminology[dashboard]" 
                                        value="{{ $school->terminology['dashboard'] ?? $defaultTerms['dashboard'] }}"
                                        placeholder="{{ $defaultTerms['dashboard'] }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="terminology_reports" class="form-label">Rapports</label>
                                    <input type="text" class="form-control" id="terminology_reports" 
                                        name="terminology[reports]" 
                                        value="{{ $school->terminology['reports'] ?? $defaultTerms['reports'] }}"
                                        placeholder="{{ $defaultTerms['reports'] }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="terminology_academic_year" class="form-label">Année académique</label>
                                    <input type="text" class="form-control" id="terminology_academic_year" 
                                        name="terminology[academic_year]" 
                                        value="{{ $school->terminology['academic_year'] ?? ($defaultTerms['academic_year'] ?? 'Année académique') }}"
                                        placeholder="Année académique">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="terminology_semester" class="form-label">Semestre</label>
                                    <input type="text" class="form-control" id="terminology_semester" 
                                        name="terminology[semester]" 
                                        value="{{ $school->terminology['semester'] ?? ($defaultTerms['semester'] ?? 'Semestre') }}"
                                        placeholder="Semestre">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3">Statuts de paiement</h6>
                                
                                <div class="mb-3">
                                    <label for="terminology_fully_paid" class="form-label">Payé intégralement</label>
                                    <input type="text" class="form-control" id="terminology_fully_paid" 
                                        name="terminology[fully_paid]" 
                                        value="{{ $school->terminology['fully_paid'] ?? ($defaultTerms['fully_paid'] ?? 'Payé intégralement') }}"
                                        placeholder="Payé intégralement">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="terminology_partially_paid" class="form-label">Partiellement payé</label>
                                    <input type="text" class="form-control" id="terminology_partially_paid" 
                                        name="terminology[partially_paid]" 
                                        value="{{ $school->terminology['partially_paid'] ?? ($defaultTerms['partially_paid'] ?? 'Partiellement payé') }}"
                                        placeholder="Partiellement payé">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="terminology_no_payment" class="form-label">Aucun paiement</label>
                                    <input type="text" class="form-control" id="terminology_no_payment" 
                                        name="terminology[no_payment]" 
                                        value="{{ $school->terminology['no_payment'] ?? ($defaultTerms['no_payment'] ?? 'Aucun paiement') }}"
                                        placeholder="Aucun paiement">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3">Personnalisation supplémentaire</h6>
                                
                                <div class="mb-3">
                                    <label for="new_term_key" class="form-label">Ajouter un nouveau terme</label>
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" id="new_term_key" 
                                            placeholder="Clé (ex: exam)">
                                        <input type="text" class="form-control" id="new_term_value" 
                                            placeholder="Valeur (ex: Contrôle)">
                                        <button class="btn btn-outline-primary" type="button" id="add_term_btn">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">
                                        Ajoutez des termes personnalisés qui ne sont pas dans la liste
                                    </div>
                                </div>
                                
                                <div id="custom_terms_container">
                                    @if($school->terminology)
                                        @foreach($school->terminology as $key => $value)
                                            @if(!array_key_exists($key, $defaultTerms))
                                                <div class="custom-term mb-2">
                                                    <div class="input-group">
                                                        <span class="input-group-text">{{ $key }}</span>
                                                        <input type="text" class="form-control" 
                                                            name="terminology[{{ $key }}]" 
                                                            value="{{ $value }}">
                                                        <button class="btn btn-outline-danger remove-term" 
                                                            type="button" data-key="{{ $key }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary" id="resetDefaultsBtn">
                                <i class="fas fa-undo me-2"></i>Réinitialiser aux valeurs par défaut
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ajouter un nouveau terme personnalisé
        document.getElementById('add_term_btn').addEventListener('click', function() {
            const keyInput = document.getElementById('new_term_key');
            const valueInput = document.getElementById('new_term_value');
            
            const key = keyInput.value.trim();
            const value = valueInput.value.trim();
            
            if (key && value) {
                const container = document.getElementById('custom_terms_container');
                
                // Vérifier si cette clé existe déjà
                if (document.querySelector(`input[name="terminology[${key}]"]`)) {
                    alert('Cette clé existe déjà. Veuillez en choisir une autre.');
                    return;
                }
                
                const html = `
                    <div class="custom-term mb-2">
                        <div class="input-group">
                            <span class="input-group-text">${key}</span>
                            <input type="text" class="form-control" 
                                name="terminology[${key}]" 
                                value="${value}">
                            <button class="btn btn-outline-danger remove-term" 
                                type="button" data-key="${key}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                
                container.insertAdjacentHTML('beforeend', html);
                
                // Reset inputs
                keyInput.value = '';
                valueInput.value = '';
                
                // Ajouter le gestionnaire d'événements au nouveau bouton
                setupRemoveButtons();
            } else {
                alert('Veuillez saisir à la fois une clé et une valeur.');
            }
        });
        
        // Réinitialiser aux valeurs par défaut
        document.getElementById('resetDefaultsBtn').addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir réinitialiser tous les termes à leurs valeurs par défaut?')) {
                @foreach($defaultTerms as $key => $value)
                    document.getElementById('terminology_{{ $key }}').value = '{{ $value }}';
                @endforeach
                
                // Supprimer tous les termes personnalisés
                const customTerms = document.querySelectorAll('.custom-term');
                customTerms.forEach(term => term.remove());
            }
        });
        
        // Configurer les boutons de suppression
        function setupRemoveButtons() {
            document.querySelectorAll('.remove-term').forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('Êtes-vous sûr de vouloir supprimer ce terme personnalisé?')) {
                        this.closest('.custom-term').remove();
                    }
                });
            });
        }
        
        // Initialiser les boutons de suppression existants
        setupRemoveButtons();
    });
</script>
@endpush
@endsection 