<?php

namespace App\Http\Controllers;

use App\Models\EducationLevel;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SchoolSettingsController extends Controller
{
    /**
     * Affiche les paramètres de l'école
     */
    public function index()
    {
        $school = School::find(session('current_school_id'));
        
        if (!$school) {
            return redirect()->route('schools.index')
                ->with('error', 'Veuillez d\'abord sélectionner une école');
        }
        
        $educationLevels = $school->educationLevels()->ordered()->get();
        
        // Types d'établissements disponibles
        $schoolTypes = [
            'primary' => 'École primaire',
            'secondary' => 'École secondaire',
            'high_school' => 'Lycée',
            'university' => 'Université/Enseignement supérieur',
            'professional' => 'Formation professionnelle',
            'language' => 'École de langues',
            'other' => 'Autre'
        ];
        
        return view('schools.settings', compact('school', 'educationLevels', 'schoolTypes'));
    }
    
    /**
     * Met à jour les paramètres généraux de l'école
     */
    public function updateGeneral(Request $request, School $school)
    {
        $this->authorize('update', $school);
        
        $validated = $request->validate([
            'school_type' => 'required|string',
            'theme_color' => 'required|string|max:7',
            'header_color' => 'required|string|max:7',
            'sidebar_color' => 'required|string|max:7',
            'text_color' => 'required|string|max:7',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_logo' => 'nullable|boolean',
        ]);
        
        // Gérer le téléchargement du logo
        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo s'il existe
            if ($school->logo && file_exists(storage_path('app/public/' . $school->logo))) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $school->logo);
            }
            
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $logoPath;
        } elseif ($request->boolean('remove_logo')) {
            // Supprimer le logo si demandé
            if ($school->logo && file_exists(storage_path('app/public/' . $school->logo))) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $school->logo);
            }
            $validated['logo'] = null;
        } else {
            // Ne pas toucher au logo s'il n'y a pas de changement
            unset($validated['logo']);
        }
        
        // Supprimer remove_logo qui n'est pas une colonne de la table
        unset($validated['remove_logo']);
        
        $school->update($validated);
        
        return redirect()->route('school.settings')
            ->with('success', 'Les paramètres généraux ont été mis à jour');
    }
    
    /**
     * Met à jour la terminologie personnalisée
     */
    public function updateTerminology(Request $request, School $school)
    {
        $this->authorize('update', $school);
        
        $validated = $request->validate([
            'terminology' => 'required|array',
        ]);
        
        $school->update([
            'terminology' => $validated['terminology']
        ]);
        
        return redirect()->route('school.settings')
            ->with('success', 'La terminologie a été mise à jour');
    }
    
    /**
     * Ajoute un nouveau niveau d'éducation
     */
    public function storeEducationLevel(Request $request, School $school)
    {
        $this->authorize('update', $school);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'duration_years' => 'required|integer|min:1',
            'order' => 'required|integer|min:0',
        ]);
        
        $school->educationLevels()->create($validated);
        
        return redirect()->route('school.settings')
            ->with('success', 'Le niveau d\'éducation a été ajouté');
    }
    
    /**
     * Met à jour un niveau d'éducation
     */
    public function updateEducationLevel(Request $request, School $school, EducationLevel $level)
    {
        $this->authorize('update', $school);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'duration_years' => 'required|integer|min:1',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        $level->update($validated);
        
        return redirect()->route('school.settings')
            ->with('success', 'Le niveau d\'éducation a été mis à jour');
    }
    
    /**
     * Supprime un niveau d'éducation
     */
    public function destroyEducationLevel(School $school, EducationLevel $level)
    {
        $this->authorize('update', $school);
        
        // Vérifier s'il y a des filières associées
        if ($level->fields()->count() > 0) {
            return redirect()->route('school.settings')
                ->with('error', 'Ce niveau d\'éducation ne peut pas être supprimé car il est utilisé par des filières');
        }
        
        $level->delete();
        
        return redirect()->route('school.settings')
            ->with('success', 'Le niveau d\'éducation a été supprimé');
    }

    /**
     * Affiche la page de gestion des widgets du tableau de bord
     *
     * @return \Illuminate\Http\Response
     */
    public function manageWidgets()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école');
        }
        
        // Récupérer les widgets actifs et inactifs
        $activeWidgets = $school->widgets()->where('is_active', true)
            ->orderBy('position')
            ->get();
            
        $inactiveWidgets = $school->widgets()->where('is_active', false)
            ->orderBy('widget_type')
            ->get();
        
        // Types de widgets disponibles
        $availableWidgetTypes = [
            'finance_summary' => 'Résumé financier',
            'student_count' => 'Nombre d\'étudiants',
            'payment_chart' => 'Graphique des paiements',
            'recent_payments' => 'Paiements récents',
            'top_fields' => 'Filières populaires',
            'payment_status' => 'Statut des paiements',
            'calendar' => 'Calendrier',
            'announcements' => 'Annonces',
            'quick_actions' => 'Actions rapides',
        ];
        
        return view('school-settings.widgets', compact('school', 'activeWidgets', 'inactiveWidgets', 'availableWidgetTypes'));
    }
    
    /**
     * Ajoute un nouveau widget au tableau de bord
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeWidget(Request $request)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école');
        }
        
        $validated = $request->validate([
            'widget_type' => 'required|string',
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
        ]);
        
        // Déterminer la dernière position
        $lastPosition = $school->widgets()->max('position') ?? 0;
        
        // Créer le widget
        $school->widgets()->create([
            'widget_type' => $validated['widget_type'],
            'title' => $validated['title'],
            'icon' => $validated['icon'],
            'position' => $lastPosition + 1,
            'is_active' => true,
        ]);
        
        return redirect()->route('school.settings.widgets')
            ->with('success', 'Widget ajouté avec succès');
    }
    
    /**
     * Met à jour l'ordre et l'état actif/inactif des widgets
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateWidgets(Request $request)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école');
        }
        
        $validated = $request->validate([
            'widgets' => 'required|array',
            'widgets.*.id' => 'required|integer|exists:school_widgets,id',
            'widgets.*.position' => 'required|integer|min:0',
            'widgets.*.is_active' => 'required|boolean',
        ]);
        
        foreach ($validated['widgets'] as $widgetData) {
            $widget = $school->widgets()->findOrFail($widgetData['id']);
            $widget->update([
                'position' => $widgetData['position'],
                'is_active' => $widgetData['is_active'],
            ]);
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Supprime un widget
     *
     * @param  int  $widgetId
     * @return \Illuminate\Http\Response
     */
    public function deleteWidget($widgetId)
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école');
        }
        
        $widget = $school->widgets()->findOrFail($widgetId);
        $widget->delete();
        
        return redirect()->route('school.settings.widgets')
            ->with('success', 'Widget supprimé avec succès');
    }

    /**
     * Affiche la page de gestion des rapports
     *
     * @return \Illuminate\Http\Response
     */
    public function reportsIndex()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école');
        }
        
        return view('school-settings.report_templates', compact('school'));
    }
    
    /**
     * Met à jour les paramètres des rapports
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\School  $school
     * @return \Illuminate\Http\Response
     */
    public function updateReports(Request $request, School $school)
    {
        $this->authorize('update', $school);
        
        $validated = $request->validate([
            'report_settings' => 'required|array',
            'report_signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_signature' => 'nullable|boolean',
        ]);
        
        // Gérer l'upload de la signature
        if ($request->hasFile('report_signature')) {
            // Supprimer l'ancienne signature si elle existe
            if (isset($school->report_settings['signature_image']) && 
                file_exists(storage_path('app/public/' . $school->report_settings['signature_image']))) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $school->report_settings['signature_image']);
            }
            
            $signaturePath = $request->file('report_signature')->store('report_signatures', 'public');
            $validated['report_settings']['signature_image'] = $signaturePath;
        } elseif ($request->boolean('remove_signature')) {
            // Supprimer la signature si demandé
            if (isset($school->report_settings['signature_image']) && 
                file_exists(storage_path('app/public/' . $school->report_settings['signature_image']))) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $school->report_settings['signature_image']);
            }
            if (isset($validated['report_settings']['signature_image'])) {
                unset($validated['report_settings']['signature_image']);
            }
        } else {
            // Conserver l'ancienne signature
            if (isset($school->report_settings['signature_image'])) {
                $validated['report_settings']['signature_image'] = $school->report_settings['signature_image'];
            }
        }
        
        // Fusionner les paramètres existants avec les nouveaux
        $reportSettings = array_merge($school->report_settings ?? [], $validated['report_settings']);
        
        $school->update([
            'report_settings' => $reportSettings
        ]);
        
        return redirect()->route('school.settings.reports.index')
            ->with('success', 'Les paramètres des rapports ont été mis à jour');
    }
    
    /**
     * Génère un aperçu du rapport avec les paramètres actuels
     *
     * @return \Illuminate\Http\Response
     */
    public function previewReport()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école');
        }
        
        // Générer un PDF d'exemple
        $pdf = PDF::loadView('reports.preview', compact('school'));
        
        return $pdf->stream('apercu_rapport.pdf');
    }
    
    /**
     * Affiche la page de gestion de l'apparence
     *
     * @return \Illuminate\Http\Response
     */
    public function appearanceIndex()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école');
        }
        
        return view('school-settings.appearance', compact('school'));
    }
    
    /**
     * Met à jour les paramètres d'apparence
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\School  $school
     * @return \Illuminate\Http\Response
     */
    public function updateAppearance(Request $request, School $school)
    {
        $this->authorize('update', $school);
        
        $validated = $request->validate([
            'theme_color' => 'required|string|max:7',
            'header_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'sidebar_color' => 'required|string|max:7',
            'text_color' => 'required|string|max:7',
            'font_family' => 'nullable|string|max:100',
            'card_style' => 'nullable|string|in:default,rounded,flat',
            'button_style' => 'nullable|string|in:default,rounded,flat',
            'layout' => 'nullable|string|in:default,compact,wide',
        ]);
        
        $school->update($validated);
        
        // Mettre à jour la session
        session(['current_school' => $school]);
        
        return redirect()->route('school.settings.appearance')
            ->with('success', 'Les paramètres d\'apparence ont été mis à jour');
    }
    
    /**
     * Affiche la page de gestion de la terminologie
     *
     * @return \Illuminate\Http\Response
     */
    public function terminologyIndex()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école');
        }
        
        // Termes par défaut
        $defaultTerms = [
            'student' => 'Étudiant',
            'students' => 'Étudiants',
            'field' => 'Filière',
            'fields' => 'Filières',
            'campus' => 'Campus',
            'campuses' => 'Campus',
            'payment' => 'Paiement',
            'payments' => 'Paiements',
            'class' => 'Classe',
            'classes' => 'Classes',
            'teacher' => 'Enseignant',
            'teachers' => 'Enseignants',
            'parent' => 'Parent',
            'parents' => 'Parents',
            'fee' => 'Frais',
            'fees' => 'Frais',
            'dashboard' => 'Tableau de bord',
            'reports' => 'Rapports',
        ];
        
        return view('school-settings.terminology', compact('school', 'defaultTerms'));
    }
    
    /**
     * Affiche la page de gestion des notifications
     *
     * @return \Illuminate\Http\Response
     */
    public function notificationsIndex()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école');
        }
        
        return view('school-settings.notifications', compact('school'));
    }
    
    /**
     * Met à jour les paramètres de notifications
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\School  $school
     * @return \Illuminate\Http\Response
     */
    public function updateNotifications(Request $request, School $school)
    {
        $this->authorize('update', $school);
        
        $validated = $request->validate([
            'has_sms_notifications' => 'boolean',
            'has_email_notifications' => 'boolean',
            'notification_settings' => 'nullable|array',
            'notification_templates' => 'nullable|array',
        ]);
        
        // Mettre à jour les paramètres
        $school->update([
            'has_sms_notifications' => $validated['has_sms_notifications'],
            'has_email_notifications' => $validated['has_email_notifications'] ?? false,
            'notification_settings' => $validated['notification_settings'] ?? $school->notification_settings ?? [],
            'notification_templates' => $validated['notification_templates'] ?? $school->notification_templates ?? [],
        ]);
        
        return redirect()->route('school.settings.notifications')
            ->with('success', 'Les paramètres de notifications ont été mis à jour');
    }
    
    /**
     * Affiche la page de gestion des documents
     *
     * @return \Illuminate\Http\Response
     */
    public function documentsIndex()
    {
        $school = session('current_school');
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école');
        }
        
        // Récupérer les modèles de documents
        $documents = $school->documents()->orderBy('name')->get();
        
        return view('school-settings.documents', compact('school', 'documents'));
    }
    
    /**
     * Enregistre un nouveau modèle de document
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\School  $school
     * @return \Illuminate\Http\Response
     */
    public function storeDocument(Request $request, School $school)
    {
        $this->authorize('update', $school);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'template_file' => 'required|file|mimes:pdf,docx|max:5120',
            'document_type' => 'required|string|in:certificate,report,receipt,letter',
        ]);
        
        // Stocker le fichier
        $filePath = $request->file('template_file')->store('document_templates', 'public');
        
        // Créer le document
        $school->documents()->create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'document_type' => $validated['document_type'],
        ]);
        
        return redirect()->route('school.settings.documents')
            ->with('success', 'Le modèle de document a été ajouté');
    }
    
    /**
     * Supprime un modèle de document
     *
     * @param  \App\Models\School  $school
     * @param  int  $document
     * @return \Illuminate\Http\Response
     */
    public function destroyDocument(School $school, $document)
    {
        $this->authorize('update', $school);
        
        $document = $school->documents()->findOrFail($document);
        
        // Supprimer le fichier
        if (file_exists(storage_path('app/public/' . $document->file_path))) {
            \Illuminate\Support\Facades\Storage::delete('public/' . $document->file_path);
        }
        
        $document->delete();
        
        return redirect()->route('school.settings.documents')
            ->with('success', 'Le modèle de document a été supprimé');
    }
}
