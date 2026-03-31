<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Favorito
 * 
 * @property int $fav_id
 * @property int $fav_fk_tienda
 * @property int $fav_fk_cliente
 * 
 * @property Tienda $tienda
 * @property Cliente $cliente
 *
 * @package App\Models
 */
class Favorito extends Model
{
	protected $table = 'favorito';
	protected $primaryKey = 'fav_id';
	public $timestamps = false;

	protected $casts = [
		'fav_fk_tienda' => 'int',
		'fav_fk_cliente' => 'int'
	];

	protected $fillable = [
		'fav_fk_tienda',
		'fav_fk_cliente'
	];

	public function tienda()
	{
		return $this->belongsTo(Tienda::class, 'fav_fk_tienda');
	}

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'fav_fk_cliente');
	}
}
