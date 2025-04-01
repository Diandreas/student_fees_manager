<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'primary_color',
        'secondary_color',
        'theme_color',
        'header_color',
        'sidebar_color',
        'text_color',
        'school_type',
        'terminology',
        'subdomain',
        'address',
        'contact_email',
        'contact_phone',
        'description',
        'is_active',
        'has_online_payments',
        'has_sms_notifications',
        'has_email_notifications',
        'has_parent_portal',
        'preferences',
        'report_settings',
        'notification_settings',
        'notification_templates',
        'subscription_plan',
        'subscription_expires_at',
        'email',
        'phone',
        'font_family',
        'card_style',
        'button_style',
        'layout',
        'currency',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_online_payments' => 'boolean',
        'has_sms_notifications' => 'boolean',
        'has_email_notifications' => 'boolean',
        'has_parent_portal' => 'boolean',
        'terminology' => 'array',
        'preferences' => 'array',
        'report_settings' => 'array',
        'notification_settings' => 'array',
        'notification_templates' => 'array',
        'subscription_expires_at' => 'datetime',
    ];

    /**
     * Get all campuses for the school
     */
    public function campuses()
    {
        return $this->hasMany(Campus::class);
    }

    /**
     * Get all education levels for the school
     */
    public function educationLevels()
    {
        return $this->hasMany(EducationLevel::class);
    }

    /**
     * Get all admin users for the school
     */
    public function admins()
    {
        return $this->belongsToMany(User::class, 'school_admins')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Get all fields for the school through campuses
     */
    public function fields()
    {
        return $this->hasManyThrough(Field::class, Campus::class);
    }

    /**
     * Get all students for the school through fields
     */
    public function students()
    {
        return $this->hasManyThrough(
            Student::class, 
            Field::class, 
            'campus_id',
            'field_id',
            null,
            'id'
        )->join('campuses', 'fields.campus_id', '=', 'campuses.id')
          ->where('campuses.school_id', $this->id);
    }

    /**
     * Get the logo url
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return asset('images/default-school-logo.png');
    }

    /**
     * Récupère la terminologie personnalisée ou la valeur par défaut
     */
    public function term($key, $default = null)
    {
        $terms = [
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
            // Statuts de paiement
            'fully_paid' => 'Payé intégralement',
            'partially_paid' => 'Partiellement payé',
            'no_payment' => 'Aucun paiement',
            'paid' => 'Payé',
            'partial' => 'Partiel',
            'unpaid' => 'Non payé',
            // Autres termes
            'status' => 'État',
            'remaining' => 'Reste à payer',
            'paid_amount' => 'Montant payé',
            'report' => 'Rapport',
            'receipt' => 'Reçu',
            'dashboard' => 'Tableau de bord',
            'statistics' => 'Statistiques',
            'profile' => 'Profil',
            'settings' => 'Paramètres',
            'administration' => 'Administration',
            'summary' => 'Résumé',
            'details' => 'Détails',
            'academic_year' => 'Année académique',
            'semester' => 'Semestre',
            'enrollment' => 'Inscription',
            'registration' => 'Enregistrement',
            'document' => 'Document',
            'documents' => 'Documents',
        ];
        
        if ($this->terminology && isset($this->terminology[$key])) {
            return $this->terminology[$key];
        }
        
        return $default ?? ($terms[$key] ?? ucfirst($key));
    }

    /**
     * Get all widgets for the school
     */
    public function widgets()
    {
        return $this->hasMany(SchoolWidget::class);
    }

    /**
     * Get active widgets for the school
     */
    public function activeWidgets()
    {
        return $this->widgets()->where('is_active', true)->orderBy('position');
    }

    /**
     * Get all documents templates for the school
     */
    public function documents()
    {
        return $this->hasMany(SchoolDocument::class);
    }

    /**
     * Get all users associated with the school
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'school_admins')
                    ->withPivot('role')
                    ->withTimestamps();
    }
}
