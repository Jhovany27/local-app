<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Repartidor
 * 
 * @property int $rep_id
 * @property string $rep_tipo_vehiculo
 * @property int $rep_estado
 * @property int $user_id
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Repartidor extends Model
{
	protected $table = 'repartidor';
	protected $primaryKey = 'rep_id';
	public $timestamps = false;

	protected $casts = [
		'rep_estado' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'rep_tipo_vehiculo',
		'rep_estado',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
