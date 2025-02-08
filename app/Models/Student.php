<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'fullName', 'email', 'address', 'phone',
        'parent_tel', 'field_id', 'user_id'
    ];

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
}
