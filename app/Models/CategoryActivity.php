<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CategoryActivity
 * 
 * @property int $ID_CATEGORY
 * @property int $ID_OUTLET
 * @property string $NAMA
 * 
 * @property Outlet $outlet
 * @property Collection|DetailActivity[] $detail_activities
 *
 * @package App\Models
 */
class CategoryActivity extends Model
{
	protected $table = 'category_activity';
	protected $primaryKey = 'ID_CATEGORY';
	protected $keyType = "string";
	use SoftDeletes;


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
