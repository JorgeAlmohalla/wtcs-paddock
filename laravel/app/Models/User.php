<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    
    use HasApiTokens, HasFactory, Notifiable; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'steam_id',
        'nationality',
        'roles',
        'team_id',
        'contract_type',
        'bio',
        'equipment',
        'avatar_url',
    ];

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
        'roles' => 'array',
    ];
    
    // RELACIÓN: Un piloto pertenece a un equipo
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function raceResults()
    {
        return $this->hasMany(RaceResult::class);
    }

    public function qualifyingResults()
    {
        return $this->hasMany(QualifyingResult::class);
    }

    public function hasRole(string $role): bool
    {
        // 1. Obtener roles
        $roles = $this->roles;

        // 2. Si es null, array vacío
        if (is_null($roles)) {
            return false;
        }

        // 3. Si por error es un string (JSON mal decodificado), lo decodificamos a mano
        if (is_string($roles)) {
            $roles = json_decode($roles, true) ?? [];
        }

        // 4. Si después de todo no es array, false
        if (!is_array($roles)) {
            return false;
        }

        return in_array($role, $roles);
    }

    public function isAdmin(): bool 
    { 
        return $this->hasRole('admin'); 
    }

    public function isSteward(): bool 
    {
        return $this->hasRole('steward'); 
    }

    public function isTeamPrincipal(): bool 
    {
        return $this->hasRole('team_principal'); 
    }
}