<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UserLog
 * 
 * @property int $id
 * @property int $user
 * @property int $outlet
 * @property int $activity
 *
 * @package App\Models
 */
class UserLog extends Model
{
	protected $table = 'user_log';
	use SoftDeletes;

	protected $fillable = [
		'user',
		'outlet',
		'activity'
	];
}
