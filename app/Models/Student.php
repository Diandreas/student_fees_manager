<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Student extends Model
{
    use LogsActivity;

    protected $fillable = [
        'fullName', 'email', 'address', 'phone',
        'parent_tel', 'field_id', 'user_id', 'photo',
        'parent_name', 'parent_email', 'parent_profession', 
        'parent_address', 'emergency_contact_name',
        'emergency_contact_tel', 'relationship', 'school_id'
    ];

    /**
     * Récupérer l'URL de la photo de l'étudiant
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/students/' . $this->photo);
        }
        return asset('images/default-student.png');
    }

    /**
     * Récupérer l'initiale du nom pour l'affichage en l'absence de photo
     */
    public function getInitialsAttribute()
    {
        return strtoupper(substr($this->fullName, 0, 1));
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
    /**
     * Get the school that this student belongs to
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Accesseur pour obtenir le nom complet au format snake_case
     * pour assurer la cohérence entre les différentes parties de l'application
     */
    public function getFullNameAttribute()
    {
        // Utiliser les attributs directement pour éviter les erreurs de propriété non définie
        return $this->attributes['fullName'] ?? '';
    }
}
