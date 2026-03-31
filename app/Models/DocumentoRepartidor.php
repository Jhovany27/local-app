<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DocumentoRepartidor
 * 
 * @property int $dor_id
 * @property string $dor_ruta
 * @property Carbon $dor_fecha
 * @property int $dor_fk_repartidor
 * @property int $dor_fk_tipo_documento
 * 
 * @property Repartidor $repartidor
 * @property TipoDocumento $tipo_documento
 *
 * @package App\Models
 */
class DocumentoRepartidor extends Model
{
	protected $table = 'documento_repartidor';
	protected $primaryKey = 'dor_id';
	public $timestamps = false;

	protected $casts = [
		'dor_fecha' => 'datetime',
		'dor_fk_repartidor' => 'int',
		'dor_fk_tipo_documento' => 'int'
	];

	protected $fillable = [
		'dor_ruta',
		'dor_fecha',
		'dor_fk_repartidor',
		'dor_fk_tipo_documento'
	];

	public function repartidor()
	{
		return $this->belongsTo(Repartidor::class, 'dor_fk_repartidor');
	}

	public function tipo_documento()
	{
		return $this->belongsTo(TipoDocumento::class, 'dor_fk_tipo_documento');
	}
}
