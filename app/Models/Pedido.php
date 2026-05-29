<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pedido
 * 
 * @property int $ped_id
 * @property string $ped_codigo
 * @property Carbon $ped_fecha_pedido
 * @property float $ped_total
 * @property string $ped_tipo_entrega
 * @property int $ped_fk_cliente
 * @property int $ped_fk_tienda
 * 
 * @property Cliente $cliente
 * @property Tienda $tienda
 *
 * @package App\Models
 */
class Pedido extends Model
{
	protected $table = 'pedido';
	protected $primaryKey = 'ped_id';
	public $timestamps = false;

	protected $casts = [
		'ped_fecha_pedido' => 'datetime',
		'ped_total' => 'float',
		'ped_fk_cliente' => 'int',
		'ped_fk_tienda' => 'int'
	];

	protected $fillable = [
		'ped_codigo',
		'ped_fecha_pedido',
		'ped_total',
		'ped_tipo_entrega',
		'ped_estado',
		'ped_fk_cliente',
		'ped_fk_tienda',
		'ped_confirmado_tienda',
	];

	public function cliente()
	{
		return $this->belongsTo(\App\Models\Cliente::class, 'ped_fk_cliente', 'cli_id');
	}

	public function tienda()
	{
		return $this->belongsTo(Tienda::class, 'ped_fk_tienda');
	}

	public function detalles()
	{
		return $this->hasMany(DetallePedido::class, 'det_fk_pedido', 'ped_id');
	}

	public function estados()
	{
		return $this->hasMany(EstadoPedido::class, 'esp_fk_pedido', 'ped_id');
	}

	public function pago()
	{
		return $this->hasOne(Pago::class, 'pag_fk_pedido', 'ped_id');
	}

	public function asignacion()
	{
		return $this->hasOne(\App\Models\AsignacionRepartidor::class, 'asr_fk_pedido', 'ped_id');
	}

	public function venta()
	{
		return $this->hasOne(Venta::class, 'ven_fk_pedido', 'ped_id');
	}
}
