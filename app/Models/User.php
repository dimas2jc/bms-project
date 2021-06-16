<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class User
 * 
 * @property string $ID_USER
 * @property string $username
 * @property string $password
 * @property string $NAMA
 * @property string|null $EMAIL
 * @property string|null $NO_TELP
 * @property int $ROLE
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	use SoftDeletes;
	protected $table = 'user';
	protected $primaryKey = 'ID_USER';
	public $incrementing = false;

	protected $casts = [
		'ROLE' => 'int'
	];

	protected $hidden = [
		'password'
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
