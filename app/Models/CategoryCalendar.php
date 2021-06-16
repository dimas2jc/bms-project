<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CategoryCalendar
 * 
 * @property string $ID_CATEGORY_CALENDAR
 * @property string $NAMA
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|Calendar[] $calendars
 * @property DetailCategoryCalendar $detail_category_calendar
 *
 * @package App\Models
 */
class CategoryCalendar extends Model
{
	use SoftDeletes;
	protected $table = 'category_calendar';
	protected $primaryKey = 'ID_CATEGORY_CALENDAR';
	public $incrementing = false;

	protected $fillable = [
		'NAMA'
	];

	public function calendars()
	{
		return $this->hasMany(Calendar::class, 'ID_CATEGORY_CALENDAR');
	}

	public function detail_category_calendar()
	{
		return $this->hasOne(DetailCategoryCalendar::class, 'ID_CATEGORY_CALENDAR');
	}
}
