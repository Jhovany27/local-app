<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Persona
 * 
 * @property int $per_id
 * @property string $per_nombre
 * @property string $per_paterno
 * @property string $per_materno
 * @property string $per_telefono
 * @property Carbon $per_fecha_registro
 * @property string $per_estado
 * @property int $user_id
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Persona extends Model
{
	protected $table = 'persona';
	protected $primaryKey = 'per_id';
	public $timestamps = false;

	protected $casts = [
		'per_fecha_registro' => 'datetime',
		'user_id' => 'int'
	];

	protected $fillable = [
		'per_nombre',
		'per_paterno',
		'per_materno',
		'per_telefono',
		'per_fecha_registro',
		'per_estado',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

		protected static function booted()
	{
		static::creating(function ($persona) {
			$persona->per_estado = 'Activo';
		});
	}

}
