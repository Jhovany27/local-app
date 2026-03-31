<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Fachada
 * 
 * @property int $fac_id
 * @property string $fac_ruta
 * @property int $fac_fk_tienda
 * 
 * @property Tienda $tienda
 *
 * @package App\Models
 */
class Fachada extends Model
{
	protected $table = 'fachada';
	protected $primaryKey = 'fac_id';
	public $timestamps = false;

	protected $casts = [
		'fac_fk_tienda' => 'int'
	];

	protected $fillable = [
		'fac_ruta',
		'fac_fk_tienda'
	];

	public function tienda()
	{
		return $this->belongsTo(Tienda::class, 'fac_fk_tienda');
	}
}
