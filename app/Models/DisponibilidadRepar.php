<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DisponibilidadRepar
 * 
 * @property int $dir_id
 * @property string $dir_estado
 * @property Carbon $dir_actualizacion
 * @property int $dir_fk_repartidor
 * 
 * @property Repartidor $repartidor
 *
 * @package App\Models
 */
class DisponibilidadRepar extends Model
{
	protected $table = 'disponibilidad_repar';
	protected $primaryKey = 'dir_id';
	public $timestamps = false;

	protected $casts = [
		'dir_actualizacion' => 'datetime',
		'dir_fk_repartidor' => 'int'
	];

	protected $fillable = [
		'dir_estado',
		'dir_actualizacion',
		'dir_fk_repartidor'
	];

	public function repartidor()
	{
		return $this->belongsTo(Repartidor::class, 'dir_fk_repartidor');
	}
}
