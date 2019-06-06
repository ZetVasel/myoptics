<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Products;
use App\Models\Orders;
use App\Models\Settings;
use Auth;
use Mail;
use Mailgun\Mailgun;

class OrdersProducts extends Model
{
	protected $table = 'dv_orders_products';
	protected $guarded = [];
	
	public static function saveOrderProducts( $cart_products, $new_order ){
		

		$user = Auth::check() ? Auth::user() : 0;

		$total_price = 0;

		if( count($cart_products) ){

			$pr_ids = array_keys( $cart_products );

			$products = Products::cartProducts( $cart_products );

			foreach ( $products as $pr ) {

				$cost = round($pr->price - $pr->price * $pr->p_discount/100, 2);

				$total_price += ( $cost * $cart_products[$pr->id] );

				$products_insert[] = [
					'order_id' => $new_order->id, 
					'product_id' => $pr->id, 
					'quantity' => $cart_products[$pr->id],
					'cost' => $cost,
				];
			}
			self::insert( $products_insert );
		}
		if(isset($user->id) && $user->id != 0)
			$total_price = $total_price - $total_price * $user->discount/100;

		return $total_price;
	}


	public static function sendOrderToClient( $id ){

	}
}
