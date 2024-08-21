<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'password', 'avatar'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi satu pengguna bisa memiliki banyak proyek.
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    /**
     * Relasi satu pengguna bisa memiliki banyak tugas.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /**
     * Implementasi metode dari JWTSubject.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Implementasi metode dari JWTSubject.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
