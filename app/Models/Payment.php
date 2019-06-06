<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
	protected $table = 'dv_payment';
	protected $guarded = [];


	public static function destroy( $ids ){

		$post = self::whereIn( 'id', $ids )->get();

		parent::destroy( $ids );
	}
}
