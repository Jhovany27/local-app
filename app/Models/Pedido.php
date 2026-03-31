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
		'ped_fk_cliente',
		'ped_fk_tienda'
	];

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'ped_fk_cliente');
	}

	public function tienda()
	{
		return $this->belongsTo(Tienda::class, 'ped_fk_tienda');
	}
}
