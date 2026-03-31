<?php

namespace App\Models;

use Carbon\Carbon;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
 */
class User extends Authenticatable
{
	protected $table = 'users';

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Models\Rol::class,
            'rol_users',
            'user_id',
            'rol_id'
        );
    }

	
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

	/**
	 * Mutator para evitar doble hash
	 */
	public function setPasswordAttribute($value)
	{
		if (!empty($value)) {
			// Si ya está hasheado, no volver a hashear
			if (password_get_info($value)['algo'] === 0) {
				$this->attributes['password'] = Hash::make($value);
			} else {
				$this->attributes['password'] = $value;
			}
		}
	}

	public function persona()
	{
		return $this->hasOne(\App\Models\Persona::class, 'user_id');
	}

	public function cliente()
	{
		return $this->hasOne(\App\Models\Cliente::class, 'user_id');
	}

	public function tienda()
	{
		return $this->hasMany(\App\Models\Tienda::class, 'user_id');
	}

	    public function hasRol(string $rol): bool
    {
        return $this->roles()->where('rol_nombre', $rol)->exists();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRol('admin');
    }
	
}
