<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'description', 'student_id', 'amount', 'payment_date', 'reference_no', 'school_id'
    ];

    // Conversion automatique de payment_date en objet Carbon
    protected $casts = [
        'payment_date' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    /**
     * Get the school that this payment belongs to
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
