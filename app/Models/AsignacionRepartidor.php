<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AsignacionRepartidor
 * 
 * @property int $asr_id
 * @property Carbon $asr_fecha
 * @property int $asr_estado
 * @property int $asr_fk_repartidor
 * @property int $asr_fk_pedido
 * 
 * @property Repartidor $repartidor
 * @property Pedido $pedido
 *
 * @package App\Models
 */
class AsignacionRepartidor extends Model
{
	protected $table = 'asignacion_repartidor';
	protected $primaryKey = 'asr_id';
	public $timestamps = false;

	protected $casts = [
		'asr_fecha' => 'datetime',
		'asr_estado' => 'int',
		'asr_fk_repartidor' => 'int',
		'asr_fk_pedido' => 'int'
	];

	protected $fillable = [
		'asr_fecha',
		'asr_estado',
		'asr_fk_repartidor',
		'asr_fk_pedido'
	];

	public function repartidor()
	{
		return $this->belongsTo(Repartidor::class, 'asr_fk_repartidor');
	}

	public function pedido()
	{
		return $this->belongsTo(Pedido::class, 'asr_fk_pedido');
	}
}
