<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EstadoPedido
 * 
 * @property int $esp_id
 * @property string $esp_nombre
 * @property Carbon $esp_fecha_cambio
 * @property int $esp_fk_pedido
 * 
 * @property Pedido $pedido
 *
 * @package App\Models
 */
class EstadoPedido extends Model
{
	protected $table = 'estado_pedido';
	protected $primaryKey = 'esp_id';
	public $timestamps = false;

	protected $casts = [
		'esp_fecha_cambio' => 'datetime',
		'esp_fk_pedido' => 'int'
	];

	protected $fillable = [
		'esp_nombre',
		'esp_fecha_cambio',
		'esp_fk_pedido'
	];

	public function pedido()
	{
		return $this->belongsTo(Pedido::class, 'esp_fk_pedido');
	}
}
