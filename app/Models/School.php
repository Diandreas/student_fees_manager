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
        'subdomain',
        'address',
        'contact_email',
        'contact_phone',
        'description',
        'is_active'
    ];

    /**
     * Get all campuses for the school
     */
    public function campuses()
    {
        return $this->hasMany(Campus::class);
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
        return $this->hasManyThrough(Student::class, Field::class, 'school_id', 'field_id');
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
}
