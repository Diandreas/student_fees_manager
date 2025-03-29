<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    protected $fillable = ['name', 'description', 'school_id'];

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
    
    /**
     * Get all students for this campus through fields
     */
    public function students()
    {
        return $this->hasManyThrough(Student::class, Field::class);
    }
}
