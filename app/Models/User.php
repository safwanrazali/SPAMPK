<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_PENTADBIR = 'pentadbir';
    public const ROLE_PENYELARAS = 'penyelaras';
    public const ROLE_ANALISIS = 'analisis';

    public const ROLES = [
        self::ROLE_PENTADBIR => 'Pentadbir Sistem',
        self::ROLE_PENYELARAS => 'Pegawai Penyelaras Analisis',
        self::ROLE_ANALISIS => 'Pegawai Analisis',
    ];

    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'created_by');
    }

    public function roleLabel(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

    public function isPentadbir(): bool
    {
        return $this->role === self::ROLE_PENTADBIR;
    }

    public function isPenyelaras(): bool
    {
        return $this->role === self::ROLE_PENYELARAS;
    }

    public function isAnalisis(): bool
    {
        return $this->role === self::ROLE_ANALISIS;
    }
}
