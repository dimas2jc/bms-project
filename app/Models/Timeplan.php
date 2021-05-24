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
 * @property int $ID_TIMEPLAN
 * @property int $ID_DETAIL_ACTIVITY
 * @property Carbon $TANGGAL_START
 * @property Carbon $TANGGAL_END
 * 
 * @property DetailActivity $detail_activity
 *
 * @package App\Models
 */
class Timeplan extends Model
{
	protected $table = 'timeplan';
	protected $primaryKey = 'ID_TIMEPLAN';
	protected $keyType = "string";
	use SoftDeletes;

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
