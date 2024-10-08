<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Timeplan
 * 
 * @property string $ID_TIMEPLAN
 * @property string $ID_DETAIL_ACTIVITY
 * @property Carbon $TANGGAL_START
 * @property Carbon $TANGGAL_END
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property DetailActivity $detail_activity
 *
 * @package App\Models
 */
class Timeplan extends Model
{
	use SoftDeletes;
	protected $table = 'timeplan';
	protected $primaryKey = 'ID_TIMEPLAN';
	public $incrementing = false;

	protected $dates = [
		'TANGGAL_START',
		'TANGGAL_END'
	];

	protected $fillable = [
		'ID_DETAIL_ACTIVITY',
		'TANGGAL_START',
		'TANGGAL_END'
	];

	public function detail_activity()
	{
		return $this->belongsTo(DetailActivity::class, 'ID_DETAIL_ACTIVITY');
	}
}
