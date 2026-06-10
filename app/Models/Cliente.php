<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cliente
 * 
 * @property int $cli_id
 * @property int $user_id
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Cliente extends Model
{
	protected $table = 'cliente';
	protected $primaryKey = 'cli_id';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'user_id'
	];

	protected $hidden = [
		'user_id',
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class, 'user_id');
	}

	public function favoritosProductos()
	{
		return $this->hasMany(Favorito::class, 'fav_fk_cliente', 'cli_id')
			->whereNotNull('fav_fk_producto');
	}

	public function favoritosTiendas()
	{
		return $this->hasMany(Favorito::class, 'fav_fk_cliente', 'cli_id')
			->whereNotNull('fav_fk_tienda');
	}
}
