<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Calificacion
 * 
 * @property int $cal_id
 * @property float $cal_puntuacion
 * @property string $cal_comentario
 * @property Carbon $cal_fecha
 * @property int $cal_fk_tienda
 * @property int $cal_fk_cliente
 * 
 * @property Tienda $tienda
 * @property Cliente $cliente
 *
 * @package App\Models
 */
class Calificacion extends Model
{
	protected $table = 'calificacion';
	protected $primaryKey = 'cal_id';
	public $timestamps = false;

	protected $casts = [
		'cal_puntuacion' => 'float',
		'cal_fecha' => 'datetime',
		'cal_fk_tienda' => 'int',
		'cal_fk_cliente' => 'int'
	];

	protected $fillable = [
		'cal_puntuacion',
		'cal_comentario',
		'cal_fecha',
		'cal_fk_tienda',
		'cal_fk_cliente'
	];

	public function tienda()
	{
		return $this->belongsTo(Tienda::class, 'cal_fk_tienda');
	}

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'cal_fk_cliente');
	}
}
