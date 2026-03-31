<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Rol
 * 
 * @property int $rol_id
 * @property string $rol_nombre
 *
 * @package App\Models
 */
class Rol extends Model
{
	protected $table = 'rol';
	protected $primaryKey = 'rol_id';
	public $timestamps = false;

	protected $fillable = [
		'rol_nombre'
	];
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Models\User::class,
            'rol_users',
            'rol_id',
            'user_id'
        );
    }




}
