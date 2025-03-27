<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolWidget extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'widget_type',
        'title',
        'icon',
        'settings',
        'position',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Relation avec l'école
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    
    /**
     * Récupérer les widgets actifs pour une école donnée
     *
     * @param int $schoolId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActiveWidgetsForSchool($schoolId)
    {
        return self::where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('position')
            ->get();
    }
} 