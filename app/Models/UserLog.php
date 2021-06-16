<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UserLog
 * 
 * @property string $id
 * @property string $user
 * @property string|null $outlet
 * @property string|null $activity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class UserLog extends Model
{
	use SoftDeletes;
	protected $table = 'user_log';
	public $incrementing = false;

	protected $fillable = [
		'user',
		'outlet',
		'activity'
	];
}
