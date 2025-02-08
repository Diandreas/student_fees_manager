<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = ['name', 'campus_id', 'fees'];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
