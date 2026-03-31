<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Producto
 * 
 * @property int $pro_id
 * @property string $pro_codigo
 * @property string $pro_nombre
 * @property string $pro_marca
 * @property string $pro_detalles
 * @property float $pro_precio_prove
 * @property float $pro_precio_venta
 * @property int $pro_estado
 * @property int $pro_fk_tienda
 * @property int $pro_fk_categoria
 * 
 * @property Tienda $tienda
 * @property CategoriaProducto $categoria_producto
 *
 * @package App\Models
 */
class Producto extends Model
{
	protected $table = 'producto';
	protected $primaryKey = 'pro_id';
	public $timestamps = false;

	protected $casts = [
		'pro_precio_prove' => 'float',
		'pro_precio_venta' => 'float',
		'pro_estado' => 'boolean',
		'pro_fk_tienda' => 'int',
		'pro_fk_categoria' => 'int'
	];

	protected $fillable = [
		'pro_codigo',
		'pro_nombre',
		'pro_marca',
		'pro_detalles',
		'pro_precio_prove',
		'pro_precio_venta',
		'pro_estado',
		'pro_fk_tienda',
		'pro_fk_categoria'
	];

	public function tienda()
	{
		return $this->belongsTo(Tienda::class, 'pro_fk_tienda');
	}

	public function categoria_producto()
	{
		return $this->belongsTo(CategoriaProducto::class, 'pro_fk_categoria');
	}

	public function fotos()
	{
		return $this->hasMany(\App\Models\FotoProducto::class, 'fop_fk_producto');
	}

	public function getFotoPrincipalAttribute(): ?string
	{
		return $this->fotos()->value('fop_ruta');
	}
}
