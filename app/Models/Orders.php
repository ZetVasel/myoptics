<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrdersProducts;

class Orders extends Model
{
	protected $table = 'dv_orders';
	protected $guarded = [];

	public function orderedproducts() {
		return $this->hasMany('App\Models\OrdersProducts', 'order_id');
	}

	public static function destroy( $ids ){

		OrdersProducts::whereIn('order_id', $ids )->delete();
		parent::destroy($ids);
	}
}
