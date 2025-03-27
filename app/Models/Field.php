<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = ['name', 'campus_id', 'education_level_id', 'fees'];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    /**
     * Get the education level of this field
     */
    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
