<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
	protected $table = 'dv_notifications';
	protected $guarded = [];


	public static function destroy( $ids ){

		$post = self::whereIn( 'id', $ids )->get();

		parent::destroy( $ids );
	}
}
