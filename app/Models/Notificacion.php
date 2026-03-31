<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Notificacion
 * 
 * @property int $not_id
 * @property string $not_mensaje
 * @property string $not_fecha
 * @property int $user_id
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Notificacion extends Model
{
	protected $table = 'notificacion';
	protected $primaryKey = 'not_id';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'not_mensaje',
		'not_fecha',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
