<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pago
 * 
 * @property int $pag_id
 * @property float $pag_monto
 * @property string $pag_estado
 * @property string $pag_metodo_pago
 * @property string|null $pag_stripe_payment_intent
 * @property string|null $pag_stripe_charge_id
 * @property Carbon $pag_fecha
 * @property int $pag_fk_pedido
 * 
 * @property Pedido $pedido
 *
 * @package App\Models
 */
class Pago extends Model
{
	protected $table = 'pago';
	protected $primaryKey = 'pag_id';
	public $timestamps = false;

	protected $casts = [
		'pag_monto' => 'float',
		'pag_fecha' => 'datetime',
		'pag_fk_pedido' => 'int'
	];

	protected $fillable = [
		'pag_monto',
		'pag_estado',
		'pag_metodo_pago',
		'pag_stripe_payment_intent',
		'pag_stripe_charge_id',
		'pag_fecha',
		'pag_fk_pedido'
	];

	public function pedido()
	{
		return $this->belongsTo(Pedido::class, 'pag_fk_pedido');
	}
}
