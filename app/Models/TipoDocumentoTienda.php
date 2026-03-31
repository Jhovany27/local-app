<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoDocumentoTienda
 * 
 * @property int $tdt_id
 * @property string $tdt_nombre
 * @property string $tdt_descripcion
 * 
 * @property Collection|DocumentoTienda[] $documento_tiendas
 *
 * @package App\Models
 */
class TipoDocumentoTienda extends Model
{
	protected $table = 'tipo_documento_tienda';
	protected $primaryKey = 'tdt_id';
	public $timestamps = false;

	protected $fillable = [
		'tdt_nombre',
		'tdt_descripcion'
	];

	public function documento_tiendas()
	{
		return $this->hasMany(DocumentoTienda::class, 'dot_fk_tipo_documento');
	}
}
