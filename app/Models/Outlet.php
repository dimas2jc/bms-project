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
 * Class Outlet
 * 
 * @property string $ID_OUTLET
 * @property string $NAMA
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|CategoryActivity[] $category_activities
 *
 * @package App\Models
 */
class Outlet extends Model
{
	use SoftDeletes;
	protected $table = 'outlet';
	protected $primaryKey = 'ID_OUTLET';
	public $incrementing = false;

	protected $fillable = [
		'NAMA'
	];

	public function category_activities()
	{
		return $this->hasMany(CategoryActivity::class, 'ID_OUTLET');
	}
}
