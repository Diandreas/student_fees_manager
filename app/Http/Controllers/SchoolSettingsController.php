<?php

namespace App\Http\Controllers;

use App\Models\EducationLevel;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SchoolSettingsController extends Controller
{
    /**
     * Affiche les paramètres de l'école
     */
    public function index(Request $request)
    {
        $school = School::findOrFail($request->session()->get('current_school_id'));
        
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
        
        return view('schools.settings', compact('school', 'schoolTypes'));
    }
    
    /**
     * Affiche le formulaire de modification du logo
     */
    public function showLogoForm(School $school)
    {
        $this->authorize('update', $school);
        
        return view('schools.settings.logo', compact('school'));
    }
    
    /**
     * Affiche le formulaire de modification des informations de contact
     */
    public function showContactForm(School $school)
    {
        $this->authorize('update', $school);
        
        return view('schools.settings.contact', compact('school'));
    }
    
    /**
     * Affiche le formulaire de modification des paramètres de facturation
     */
    public function showBillingForm(School $school)
    {
        $this->authorize('update', $school);
        
        return view('schools.settings.billing', compact('school'));
    }
    
    /**
     * Affiche le formulaire de modification du statut
     */
    public function showStatusForm(School $school)
    {
        $this->authorize('update', $school);
        
        return view('schools.settings.status', compact('school'));
    }
    
    /**
     * Affiche le formulaire de modification de l'en-tête des documents
     */
    public function showHeaderForm(School $school)
    {
        $this->authorize('update', $school);
        
        return view('schools.settings.header', compact('school'));
    }
    
    /**
     * Met à jour les informations générales de l'école
     */
    public function updateGeneral(Request $request, School $school)
    {
        $this->authorize('update', $school);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $school->update($validated);
        
        return redirect()->route('schools.settings.index', $school)
            ->with('success', 'Les informations générales ont été mises à jour');
    }
    
    /**
     * Met à jour le logo de l'école
     */
    public function updateLogo(Request $request, School $school)
    {
        $this->authorize('update', $school);
        
        $validated = $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Supprimer l'ancien logo s'il existe
        if ($school->logo && Storage::exists('public/' . $school->logo)) {
            Storage::delete('public/' . $school->logo);
        }
        
        // Enregistrer le nouveau logo
        $logoPath = $request->file('logo')->store('logos', 'public');
        
        $school->update([
            'logo' => $logoPath
        ]);
        
        return redirect()->route('schools.settings.index', $school)
            ->with('success', 'Le logo a été mis à jour avec succès');
    }
    
    /**
     * Met à jour les paramètres d'en-tête des documents
     */
    public function updateDocumentHeader(Request $request, School $school)
    {
        $this->authorize('update', $school);
        
        $validated = $request->validate([
            'report_settings.header_title' => 'nullable|string|max:255',
            'report_settings.header_subtitle' => 'nullable|string|max:255',
            'report_settings.header_email' => 'nullable|email|max:255',
            'report_settings.header_phone' => 'nullable|string|max:45',
            'report_settings.header_address' => 'nullable|string',
            'report_settings.header_footer' => 'nullable|string',
        ]);
        
        // Fusionner avec les paramètres existants pour ne pas écraser les autres valeurs
        $currentSettings = $school->report_settings ?? [];
        $reportSettings = array_merge($currentSettings, $validated['report_settings']);
        
        $school->update([
            'report_settings' => $reportSettings
        ]);
        
        return redirect()->route('schools.settings.index', $school)
            ->with('success', 'L\'en-tête des documents a été mis à jour');
    }
    
    /**
     * Met à jour les paramètres de facturation
     */
    public function updateBilling(Request $request, School $school)
    {
        $this->authorize('update', $school);
        
        $validated = $request->validate([
            'currency' => 'required|string|in:XAF,EUR,USD',
        ]);
        
        $school->update($validated);
        
        return redirect()->route('schools.settings.index', $school)
            ->with('success', 'Les paramètres de facturation ont été mis à jour');
    }
    
    /**
     * Met à jour le statut de l'école
     */
    public function updateStatus(Request $request, School $school)
    {
        $this->authorize('update', $school);
        
        $validated = $request->validate([
            'is_active' => 'boolean',
        ]);
        
        $school->update($validated);
        
        return redirect()->route('schools.settings.index', $school)
            ->with('success', 'Le statut de l\'établissement a été mis à jour');
    }
    
    /**
     * Met à jour les paramètres de notification
     */
    public function updateNotifications(Request $request, School $school)
    {
        $this->authorize('update', $school);
        
        $validated = $request->validate([
            'has_sms_notifications' => 'nullable|boolean',
            'has_email_notifications' => 'nullable|boolean',
            'has_online_payments' => 'nullable|boolean',
        ]);
        
        // Valeurs par défaut
        $data = [
            'has_sms_notifications' => $validated['has_sms_notifications'] ?? false,
            'has_email_notifications' => $validated['has_email_notifications'] ?? false,
            'has_online_payments' => $validated['has_online_payments'] ?? false,
        ];
        
        $school->update($data);
        
        return redirect()->route('schools.settings.index', $school)
            ->with('success', 'Les paramètres de notification ont été mis à jour');
    }
}