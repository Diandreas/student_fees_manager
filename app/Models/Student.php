<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Student extends Model
{
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
            return Storage::url('students/' . $this->photo);
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
}
