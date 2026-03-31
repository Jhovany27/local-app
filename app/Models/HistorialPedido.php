<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HistorialPedido
 * 
 * @property int $hip_id
 * @property Carbon $hip_fecha
 * @property int $hip_fk_estado
 * 
 * @property EstadoPedido $estado_pedido
 *
 * @package App\Models
 */
class HistorialPedido extends Model
{
	protected $table = 'historial_pedido';
	protected $primaryKey = 'hip_id';
	public $timestamps = false;

	protected $casts = [
		'hip_fecha' => 'datetime',
		'hip_fk_estado' => 'int'
	];

	protected $fillable = [
		'hip_fecha',
		'hip_fk_estado'
	];

	public function estado_pedido()
	{
		return $this->belongsTo(EstadoPedido::class, 'hip_fk_estado');
	}
}
