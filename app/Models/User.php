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
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'job_title',
        'address',
        'bio',
        'language',
        'theme',
        'email_notifications',
        'browser_notifications',
        'is_superadmin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'email_notifications' => 'boolean',
        'browser_notifications' => 'boolean',
        'password' => 'hashed',
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

    /**
     * Vérifie si l'utilisateur est un administrateur
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->isSchoolAdmin();
    }
    
    /**
     * Vérifie si l'utilisateur est un administrateur d'au moins une école
     *
     * @return bool
     */
    public function isSchoolAdmin(): bool
    {
        return $this->belongsToMany(School::class, 'school_admins')
            ->withPivot('role')
            ->where('role', 'admin')
            ->exists();
    }
}
