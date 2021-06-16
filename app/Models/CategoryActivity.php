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
 * Class CategoryActivity
 * 
 * @property string $ID_CATEGORY
 * @property string $ID_OUTLET
 * @property string $NAMA
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Outlet $outlet
 * @property Collection|DetailActivity[] $detail_activities
 *
 * @package App\Models
 */
class CategoryActivity extends Model
{
	use SoftDeletes;
	protected $table = 'category_activity';
	protected $primaryKey = 'ID_CATEGORY';
	public $incrementing = false;

	protected $fillable = [
		'ID_OUTLET',
		'NAMA'
	];

	public function outlet()
	{
		return $this->belongsTo(Outlet::class, 'ID_OUTLET');
	}

	public function detail_activities()
	{
		return $this->hasMany(DetailActivity::class, 'ID_CATEGORY');
	}
}
