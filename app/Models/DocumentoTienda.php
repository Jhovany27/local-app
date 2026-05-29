<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DocumentoTienda
 * 
 * @property int $dot_id
 * @property string $dot_ruta
 * @property Carbon $dot_fecha
 * @property int $dot_fk_tienda
 * @property int $dot_fk_tipo_documento
 * 
 * @property Tienda $tienda
 * @property TipoDocumentoTienda $tipo_documento_tienda
 *
 * @package App\Models
 */
class DocumentoTienda extends Model
{
	protected $table = 'documento_tienda';
	protected $primaryKey = 'dot_id';
	public $timestamps = false;

	protected $casts = [
		'dot_fecha' => 'datetime',
		'dot_fk_tienda' => 'int',
		'dot_fk_tipo_documento' => 'int'
	];

	protected $fillable = [
		'dot_ruta',
		'dot_fecha',
		'dot_fk_tienda',
		'dot_fk_tipo_documento'
	];

	public function tienda()
	{
		return $this->belongsTo(Tienda::class, 'dot_fk_tienda');
	}

	public function tipo_documento_tienda()
	{
		return $this->belongsTo(TipoDocumentoTienda::class, 'dot_fk_tipo_documento');
	}

	public function tipo_documento()
	{
		return $this->belongsTo(\App\Models\TipoDocumentoTienda::class, 'dot_fk_tipo_documento', 'tdt_id');
	}
}
