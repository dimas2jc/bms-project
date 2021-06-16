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
 * Class DetailActivity
 * 
 * @property string $ID_DETAIL_ACTIVITY
 * @property string $ID_CATEGORY
 * @property string $NAMA_AKTIFITAS
 * @property int $STATUS
 * @property int|null $FLAG
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property CategoryActivity $category_activity
 * @property Collection|Progress[] $progress
 * @property Collection|Timeplan[] $timeplans
 *
 * @package App\Models
 */
class DetailActivity extends Model
{
	use SoftDeletes;
	protected $table = 'detail_activity';
	protected $primaryKey = 'ID_DETAIL_ACTIVITY';
	public $incrementing = false;

	protected $casts = [
		'STATUS' => 'int',
		'FLAG' => 'int'
	];

	protected $fillable = [
		'ID_CATEGORY',
		'NAMA_AKTIFITAS',
		'STATUS',
		'FLAG'
	];

	public function category_activity()
	{
		return $this->belongsTo(CategoryActivity::class, 'ID_CATEGORY');
	}

	public function progress()
	{
		return $this->hasMany(Progress::class, 'ID_DETAIL_ACTIVITY');
	}

	public function timeplans()
	{
		return $this->hasMany(Timeplan::class, 'ID_DETAIL_ACTIVITY');
	}
}
