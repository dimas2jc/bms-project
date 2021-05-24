<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class User
 * 
 * @property int $ID_USER
 * @property string $USERNAME
 * @property string $PASSWORD
 * @property string $NAMA
 * @property string|null $EMAIL
 * @property string|null $NO_TELP
 * @property int $ROLE
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	protected $table = 'user';
	protected $primaryKey = 'ID_USER';
	protected $keyType = "string";
	use SoftDeletes;

	protected $casts = [
		'ROLE' => 'int'
	];

	protected $fillable = [
		'username',
		'password',
		'NAMA',
		'EMAIL',
		'NO_TELP',
		'ROLE'
	];
}
