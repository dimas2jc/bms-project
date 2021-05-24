<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Progress
 * 
 * @property int $ID_PROGRESS
 * @property int $ID_DETAIL_ACTIVITY
 * @property string $PROGRESS
 * @property string|null $KETERANGAN
 * @property string|null $FILE
 * 
 * @property DetailActivity $detail_activity
 *
 * @package App\Models
 */
class Progress extends Model
{
	protected $table = 'progress';
	protected $primaryKey = 'ID_PROGRESS';
	protected $keyType = "string";
	use SoftDeletes;


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
