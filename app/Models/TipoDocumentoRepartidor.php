<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoDocumentoRepartidor
 * 
 * @property int $tid_id
 * @property string $tid_nombre
 * @property string $tid_descripcion
 *
 * @package App\Models
 */
class TipoDocumentoRepartidor extends Model
{
	protected $table = 'tipo_documento_repartidor';
	protected $primaryKey = 'tid_id';
	public $timestamps = false;

	protected $fillable = [
		'tid_nombre',
		'tid_descripcion'
	];
}
