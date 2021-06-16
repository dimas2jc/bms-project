<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Calendar
 * 
 * @property string $ID_CALENDAR
 * @property string $ID_CATEGORY_CALENDAR
 * @property string $JUDUL
 * @property string $DESKRIPSI
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property CategoryCalendar $category_calendar
 *
 * @package App\Models
 */
class Calendar extends Model
{
	use SoftDeletes;
	protected $table = 'calendar';
	protected $primaryKey = 'ID_CALENDAR';
	public $incrementing = false;

	protected $dates = [
		'TANGGAL_START',
		'TANGGAL_END'
	];

	protected $fillable = [
		'ID_CATEGORY_CALENDAR',
		'JUDUL',
		'DESKRIPSI',
		'TANGGAL_START',
		'TANGGAL_END'
	];

	public function category_calendar()
	{
		return $this->belongsTo(CategoryCalendar::class, 'ID_CATEGORY_CALENDAR');
	}
}
