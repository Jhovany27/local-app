<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tienda
 *
 * @property int $tie_id
 * @property string $tie_nombre
 * @property string $tie_descripcion
 * @property string $tie_telefono
 * @property float $tie_latitud
 * @property float $tie_longitud
 * @property string $tie_direccion
 * @property int $tie_estado
 * @property Carbon $tie_fecha_registro
 * @property int $user_id
 *
 * @property User $user
 * @property Collection|DocumentoTienda[] $documento_tiendas
 *
 * @package App\Models
 */
class Tienda extends Model
{
    const ESTADO_PENDIENTE = 0;
    const ESTADO_APROBADA  = 1;
    const ESTADO_RECHAZADA = 2;

    protected $table      = 'tienda';
    protected $primaryKey = 'tie_id';
    public $timestamps    = false;

    protected $casts = [
        'tie_latitud'        => 'float',
        'tie_longitud'       => 'float',
        'tie_estado'         => 'integer',
        'tie_fecha_registro' => 'datetime',
        'user_id'            => 'int',
    ];

    protected $fillable = [
        'tie_nombre',
        'tie_descripcion',
        'tie_telefono',
        'tie_latitud',
        'tie_longitud',
        'tie_direccion',
        'tie_fecha_registro',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documento_tiendas()
    {
        return $this->hasMany(DocumentoTienda::class, 'dot_fk_tienda');
    }

    public function documentos()
    {
        return $this->hasMany(DocumentoTienda::class, 'dot_fk_tienda', 'tie_id');
    }

    public function fachada()
    {
        return $this->hasOne(\App\Models\Fachada::class, 'fac_fk_tienda', 'tie_id');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'ven_fk_tienda', 'tie_id');
    }

    public function favoritos()
    {
        return $this->hasMany(Favorito::class, 'fav_fk_tienda', 'tie_id');
    }
}