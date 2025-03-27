@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar des paramètres -->
        @include('school-settings.partials.sidebar')
        
        <!-- Contenu principal -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2 text-primary-custom">Paramètres de notifications</h1>
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
                        Canaux de notifications
                    </h5>
                    <p class="text-muted mb-0">
                        Configurez les méthodes de notification aux utilisateurs
                    </p>
                </div>
                <div class="card-body">
                    <form action="{{ route('school.settings.notifications.update', $school) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="has_email_notifications" 
                                        name="has_email_notifications" value="1" 
                                        {{ $school->has_email_notifications ? 'checked' : '' }}>
                                    <label class="form-check-label" for="has_email_notifications">
                                        Activer les notifications par email
                                    </label>
                                </div>
                                
                                <div class="email-settings {{ $school->has_email_notifications ? '' : 'opacity-50' }}">
                                    <div class="mb-3">
                                        <label for="notification_settings_email_sender" class="form-label">Nom de l'expéditeur</label>
                                        <input type="text" class="form-control" id="notification_settings_email_sender" 
                                            name="notification_settings[email_sender]" 
                                            value="{{ $school->notification_settings['email_sender'] ?? $school->name }}"
                                            placeholder="{{ $school->name }}">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="notification_settings_reply_to" class="form-label">Adresse de réponse (Reply-To)</label>
                                        <input type="email" class="form-control" id="notification_settings_reply_to" 
                                            name="notification_settings[reply_to]" 
                                            value="{{ $school->notification_settings['reply_to'] ?? $school->contact_email }}"
                                            placeholder="{{ $school->contact_email }}">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="notification_settings_email_footer" class="form-label">Pied de page des emails</label>
                                        <textarea class="form-control" id="notification_settings_email_footer" 
                                            name="notification_settings[email_footer]" rows="3"
                                            placeholder="Ex: © {{ date('Y') }} {{ $school->name }}. Tous droits réservés.">{{ $school->notification_settings['email_footer'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="has_sms_notifications" 
                                        name="has_sms_notifications" value="1" 
                                        {{ $school->has_sms_notifications ? 'checked' : '' }}>
                                    <label class="form-check-label" for="has_sms_notifications">
                                        Activer les notifications par SMS
                                    </label>
                                </div>
                                
                                <div class="sms-settings {{ $school->has_sms_notifications ? '' : 'opacity-50' }}">
                                    <div class="mb-3">
                                        <label for="notification_settings_sms_sender" class="form-label">Nom de l'expéditeur SMS</label>
                                        <input type="text" class="form-control" id="notification_settings_sms_sender" 
                                            name="notification_settings[sms_sender]" 
                                            value="{{ $school->notification_settings['sms_sender'] ?? '' }}"
                                            placeholder="Max 11 caractères (ex: EcoleABC)" maxlength="11">
                                        <div class="form-text">Certains opérateurs limitent à 11 caractères sans espaces ni caractères spéciaux</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="notification_settings_sms_signature" class="form-label">Signature SMS</label>
                                        <input type="text" class="form-control" id="notification_settings_sms_signature" 
                                            name="notification_settings[sms_signature]" 
                                            value="{{ $school->notification_settings['sms_signature'] ?? '' }}"
                                            placeholder="Ajouté à la fin de chaque SMS">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mb-3">Notifications systèmes</h6>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="notification_settings_new_payment" 
                                        name="notification_settings[new_payment]" value="1" 
                                        {{ ($school->notification_settings['new_payment'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="notification_settings_new_payment">
                                        Notification de nouveau paiement
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="notification_settings_payment_reminder" 
                                        name="notification_settings[payment_reminder]" value="1" 
                                        {{ ($school->notification_settings['payment_reminder'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="notification_settings_payment_reminder">
                                        Rappels de paiements en retard
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="notification_settings_new_student" 
                                        name="notification_settings[new_student]" value="1" 
                                        {{ ($school->notification_settings['new_student'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="notification_settings_new_student">
                                        Notification de nouvel étudiant
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="notification_settings_payment_receipt" 
                                        name="notification_settings[payment_receipt]" value="1" 
                                        {{ ($school->notification_settings['payment_receipt'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="notification_settings_payment_receipt">
                                        Envoi automatique des reçus de paiement
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="notification_settings_welcome_message" 
                                        name="notification_settings[welcome_message]" value="1" 
                                        {{ ($school->notification_settings['welcome_message'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="notification_settings_welcome_message">
                                        Message de bienvenue aux nouveaux étudiants
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="notification_settings_system_reports" 
                                        name="notification_settings[system_reports]" value="1" 
                                        {{ ($school->notification_settings['system_reports'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="notification_settings_system_reports">
                                        Rapports périodiques d'activité
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Modèles de messages</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="notification_templates_welcome" class="form-label">Message de bienvenue</label>
                                    <textarea class="form-control" id="notification_templates_welcome" 
                                        name="notification_templates[welcome]" rows="3">{{ $school->notification_templates['welcome'] ?? "Bienvenue à {student_name}! Nous sommes ravis de vous accueillir à {school_name}." }}</textarea>
                                    <div class="form-text">
                                        Variables disponibles: {student_name}, {school_name}, {campus_name}, {field_name}
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="notification_templates_payment_receipt" class="form-label">Reçu de paiement</label>
                                    <textarea class="form-control" id="notification_templates_payment_receipt" 
                                        name="notification_templates[payment_receipt]" rows="3">{{ $school->notification_templates['payment_receipt'] ?? "Votre paiement de {amount} a été reçu. Référence: {reference}. Merci!" }}</textarea>
                                    <div class="form-text">
                                        Variables disponibles: {student_name}, {amount}, {reference}, {date}, {school_name}
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="notification_templates_payment_reminder" class="form-label">Rappel de paiement</label>
                                    <textarea class="form-control" id="notification_templates_payment_reminder" 
                                        name="notification_templates[payment_reminder]" rows="3">{{ $school->notification_templates['payment_reminder'] ?? "Rappel: un paiement de {amount} est dû pour {student_name}. Date limite: {due_date}." }}</textarea>
                                    <div class="form-text">
                                        Variables disponibles: {student_name}, {amount}, {due_date}, {remaining}, {school_name}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary" id="previewEmailBtn">
                                <i class="fas fa-eye me-2"></i>Prévisualiser un email
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary-custom">
                        Historique des notifications
                    </h5>
                    <p class="text-muted mb-0">
                        Dernières notifications envoyées
                    </p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Destinataire</th>
                                    <th>Canal</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Exemple de notifications, à remplacer par des données réelles -->
                                <tr>
                                    <td>{{ \Carbon\Carbon::now()->subHours(2)->format('d/m/Y H:i') }}</td>
                                    <td>Reçu de paiement</td>
                                    <td>student@example.com</td>
                                    <td><span class="badge bg-info">Email</span></td>
                                    <td><span class="badge bg-success">Envoyé</span></td>
                                </tr>
                                <tr>
                                    <td>{{ \Carbon\Carbon::now()->subDays(1)->format('d/m/Y H:i') }}</td>
                                    <td>Rappel de paiement</td>
                                    <td>+1234567890</td>
                                    <td><span class="badge bg-warning">SMS</span></td>
                                    <td><span class="badge bg-success">Envoyé</span></td>
                                </tr>
                                <tr>
                                    <td>{{ \Carbon\Carbon::now()->subDays(2)->format('d/m/Y H:i') }}</td>
                                    <td>Message de bienvenue</td>
                                    <td>newstudent@example.com</td>
                                    <td><span class="badge bg-info">Email</span></td>
                                    <td><span class="badge bg-danger">Échec</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de prévisualisation d'email -->
<div class="modal fade" id="previewEmailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Prévisualisation de l'email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="border rounded p-3 mb-3" id="emailPreviewContent">
                    <div style="padding: 20px; border-bottom: 2px solid #eee;">
                        <img src="{{ $school->logo_url }}" alt="{{ $school->name }}" style="max-height: 60px;">
                    </div>
                    <div style="padding: 20px; font-family: Arial, sans-serif;">
                        <h2 style="color: {{ $school->theme_color ?? '#1a56db' }};">Message de bienvenue</h2>
                        <p>Bienvenue à <strong>Jean Dupont</strong>! Nous sommes ravis de vous accueillir à <strong>{{ $school->name }}</strong>.</p>
                        <p>Nous espérons que votre parcours éducatif avec nous sera enrichissant et productif.</p>
                        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #666;">
                            <p>{{ $school->notification_settings['email_footer'] ?? '© ' . date('Y') . ' ' . $school->name . '. Tous droits réservés.' }}</p>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group mb-0">
                        <label for="previewSelector" class="form-label">Type de message</label>
                        <select class="form-select" id="previewSelector">
                            <option value="welcome">Message de bienvenue</option>
                            <option value="payment_receipt">Reçu de paiement</option>
                            <option value="payment_reminder">Rappel de paiement</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-outline-secondary align-self-end" id="refreshPreviewBtn">
                        <i class="fas fa-sync-alt me-2"></i>Rafraîchir
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de l'activation/désactivation des sections
        function updateSectionsVisibility() {
            const emailEnabled = document.getElementById('has_email_notifications').checked;
            const smsEnabled = document.getElementById('has_sms_notifications').checked;
            
            document.querySelector('.email-settings').classList.toggle('opacity-50', !emailEnabled);
            document.querySelector('.sms-settings').classList.toggle('opacity-50', !smsEnabled);
        }
        
        document.getElementById('has_email_notifications').addEventListener('change', updateSectionsVisibility);
        document.getElementById('has_sms_notifications').addEventListener('change', updateSectionsVisibility);
        
        // Initialiser l'état des sections
        updateSectionsVisibility();
        
        // Prévisualisation des emails
        const previewEmailModal = new bootstrap.Modal(document.getElementById('previewEmailModal'));
        
        document.getElementById('previewEmailBtn').addEventListener('click', function() {
            previewEmailModal.show();
        });
        
        // Changer le contenu de la prévisualisation selon le type sélectionné
        document.getElementById('previewSelector').addEventListener('change', updatePreview);
        document.getElementById('refreshPreviewBtn').addEventListener('click', updatePreview);
        
        function updatePreview() {
            const type = document.getElementById('previewSelector').value;
            const previewContent = document.getElementById('emailPreviewContent');
            
            let title = '';
            let content = '';
            
            if (type === 'welcome') {
                title = 'Message de bienvenue';
                content = document.getElementById('notification_templates_welcome').value
                    .replace('{student_name}', 'Jean Dupont')
                    .replace('{school_name}', '{{ $school->name }}')
                    .replace('{campus_name}', 'Campus Principal')
                    .replace('{field_name}', 'Informatique');
            } else if (type === 'payment_receipt') {
                title = 'Reçu de paiement';
                content = document.getElementById('notification_templates_payment_receipt').value
                    .replace('{student_name}', 'Jean Dupont')
                    .replace('{amount}', '500 €')
                    .replace('{reference}', 'PAY-2023-001')
                    .replace('{date}', '{{ date("d/m/Y") }}')
                    .replace('{school_name}', '{{ $school->name }}');
            } else if (type === 'payment_reminder') {
                title = 'Rappel de paiement';
                content = document.getElementById('notification_templates_payment_reminder').value
                    .replace('{student_name}', 'Jean Dupont')
                    .replace('{amount}', '500 €')
                    .replace('{due_date}', '{{ \Carbon\Carbon::now()->addDays(7)->format("d/m/Y") }}')
                    .replace('{remaining}', '300 €')
                    .replace('{school_name}', '{{ $school->name }}');
            }
            
            // Mise à jour du contenu de prévisualisation
            previewContent.innerHTML = `
                <div style="padding: 20px; border-bottom: 2px solid #eee;">
                    <img src="{{ $school->logo_url }}" alt="{{ $school->name }}" style="max-height: 60px;">
                </div>
                <div style="padding: 20px; font-family: Arial, sans-serif;">
                    <h2 style="color: {{ $school->theme_color ?? '#1a56db' }};">${title}</h2>
                    <p>${content.replace(/\n/g, '<br>')}</p>
                    <p>Nous restons à votre disposition pour toute information complémentaire.</p>
                    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #666;">
                        <p>${document.getElementById('notification_settings_email_footer').value || '© {{ date("Y") }} {{ $school->name }}. Tous droits réservés.'}</p>
                    </div>
                </div>
            `;
        }
    });
</script>
@endpush
@endsection 