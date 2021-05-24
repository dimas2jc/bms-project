<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Outlet
 * 
 * @property int $ID_OUTLET
 * @property string $NAMA
 * 
 * @property Collection|CategoryActivity[] $category_activities
 *
 * @package App\Models
 */
class Outlet extends Model
{
	protected $table = 'outlet';
	protected $primaryKey = 'ID_OUTLET';
	protected $keyType = "string";
	use SoftDeletes;

	protected $fillable = [
		'ID_OTLET',
		'NAMA'
	];

	public function category_activities()
	{
		return $this->hasMany(CategoryActivity::class, 'ID_OUTLET');
	}
}
