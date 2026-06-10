<?php

namespace App\Models;

use Carbon\Carbon;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Cliente[] $clientes
 * @property Collection|Direccion[] $direccions
 * @property Collection|Notificacion[] $notificacions
 * @property Collection|Persona[] $personas
 * @property Collection|Repartidor[] $repartidors
 * @property Collection|Rol[] $rols
 * @property Collection|Tienda[] $tiendas
 */
class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    protected $table = 'users';
    use Notifiable;
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
    ];

    // ── Relaciones ──────────────────────────────────────

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Models\Rol::class,
            'role_user',
            'user_id',
            'usr_fk_rol',
            'id',
            'rol_id'
        );
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function direccions()
    {
        return $this->hasMany(Direccion::class);
    }

    public function notificacions()
    {
        return $this->hasMany(Notificacion::class);
    }

    public function personas()
    {
        return $this->hasMany(Persona::class);
    }

    public function repartidors()
    {
        return $this->hasMany(\App\Models\Repartidor::class, 'user_id');
    }
    public function persona()
    {
        return $this->hasOne(Persona::class, 'user_id');
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'user_id');
    }

    public function tienda()
    {
        return $this->hasMany(Tienda::class, 'user_id');
    }

    public function tiendas()
    {
        return $this->hasMany(Tienda::class, 'user_id', 'id');
    }

    // ── Helpers ─────────────────────────────────────────

    public function setPasswordAttribute($value)
    {
        if (empty($value)) return;

        $info = password_get_info($value);

        if (($info['algoName'] ?? null) === 'bcrypt') {
            $this->attributes['password'] = $value;
        } else {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    public function hasRol(string $rol): bool
    {
        return $this->roles()->where('rol_nombre', strtolower($rol))->exists();
    }

    public function tiendaPendiente(): bool
    {
        return $this->tiendas()
            ->where('tie_estado', Tienda::ESTADO_PENDIENTE)
            ->exists();
    }

    public function tiendaRechazada(): bool
    {
        return $this->tiendas()
            ->where('tie_estado', Tienda::ESTADO_RECHAZADA)
            ->exists();
    }


    public function tiendaAprobada(): bool
    {
        return $this->tiendas()
            ->where('tie_estado', Tienda::ESTADO_APROBADA)
            ->exists();
    }

    public function ultimaTienda()
    {
        return $this->tiendas()->latest('tie_id')->first();
    }

    // ── Filament ─────────────────────────────────────────

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin'  => $this->hasRol('admin'),
            'store'  => $this->hasRol('tienda'),
            'client' => $this->hasRol('cliente'),
            'driver' => $this->hasRol('repartidor'),
            default  => false,
        };
    }
}
