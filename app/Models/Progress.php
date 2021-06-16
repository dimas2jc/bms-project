<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Progress
 * 
 * @property string $ID_PROGRESS
 * @property string $ID_DETAIL_ACTIVITY
 * @property string|null $PROGRESS
 * @property string|null $KETERANGAN
 * @property string|null $FILE
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property DetailActivity $detail_activity
 *
 * @package App\Models
 */
class Progress extends Model
{
	use SoftDeletes;
	protected $table = 'progress';
	protected $primaryKey = 'ID_PROGRESS';
	public $incrementing = false;

	protected $fillable = [
		'ID_DETAIL_ACTIVITY',
		'PROGRESS',
		'KETERANGAN',
		'FILE'
	];

	public function detail_activity()
	{
		return $this->belongsTo(DetailActivity::class, 'ID_DETAIL_ACTIVITY');
	}
}
