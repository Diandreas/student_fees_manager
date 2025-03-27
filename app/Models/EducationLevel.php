<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EducationLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'code',
        'description',
        'order',
        'duration_years',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'duration_years' => 'integer',
    ];

    /**
     * Relation avec l'école
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Relation avec les classes/filières
     * Une classe/filière peut avoir un niveau d'éducation
     */
    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    /**
     * Retourne les niveaux d'éducation triés par ordre
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Retourne uniquement les niveaux actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
