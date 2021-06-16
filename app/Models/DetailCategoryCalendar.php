<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DetailCategoryCalendar
 * 
 * @property string $ID_OUTLET
 * @property string $ID_CATEGORY_CALENDAR
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property CategoryCalendar $category_calendar
 *
 * @package App\Models
 */
class DetailCategoryCalendar extends Model
{
	use SoftDeletes;
	protected $table = 'detail_category_calendar';
	public $incrementing = false;

	protected $fillable = [
		'ID_OUTLET',
		'ID_CATEGORY_CALENDAR'
	];

	public function category_calendar()
	{
		return $this->belongsTo(CategoryCalendar::class, 'ID_CATEGORY_CALENDAR');
	}
}
