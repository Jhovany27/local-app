<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RoleUser
 * 
 * @property int $usr_id
 * @property int $usr_fk_rol
 * @property int $user_id
 * 
 * @property User $user
 * @property Rol $rol
 *
 * @package App\Models
 */
class RoleUser extends Model
{
	protected $table = 'role_user';
	protected $primaryKey = 'usr_id';
	public $timestamps = false;

	protected $casts = [
		'usr_fk_rol' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'usr_fk_rol',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function rol()
	{
		return $this->belongsTo(Rol::class, 'usr_fk_rol');
	}
}
