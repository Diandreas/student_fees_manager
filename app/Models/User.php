<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_superadmin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the student associated with the user.
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Get schools that the user administers.
     */
    public function schools()
    {
        return $this->belongsToMany(School::class, 'school_admins')
                    ->withPivot('role', 'permissions')
                    ->withTimestamps();
    }

    /**
     * Check if user is an admin of a school.
     */
    public function isAdminOf(School $school)
    {
        return $this->schools()->where('school_id', $school->id)->exists();
    }

    /**
     * Get the role of the user in a school.
     */
    public function roleIn(School $school)
    {
        $relation = $this->schools()->where('school_id', $school->id)->first();
        return $relation ? $relation->pivot->role : null;
    }
}
