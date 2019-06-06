<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
	protected $table = 'dv_delivery';
	protected $guarded = [];


	public static function destroy( $ids ){

		$post = self::whereIn( 'id', $ids )->get();

		parent::destroy( $ids );
	}
}
