<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'description', 'student_id', 'amount', 'payment_date'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
