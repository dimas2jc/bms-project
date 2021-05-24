<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DetailActivity
 * 
 * @property int $ID_DETAIL_ACTIVITY
 * @property int $ID_CATEGORY
 * @property string $NAMA_AKTIFITAS
 * @property int $STATUS
 * @property int|null $FLAG
 * 
 * @property CategoryActivity $category_activity
 * @property Collection|Progress[] $progress
 * @property Collection|Timeplan[] $timeplans
 *
 * @package App\Models
 */
class DetailActivity extends Model
{
	protected $table = 'detail_activity';
	protected $primaryKey = 'ID_DETAIL_ACTIVITY';
	protected $keyType = "string";
	use SoftDeletes;

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
