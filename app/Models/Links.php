<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Image;
use File;
class Links extends Model {

	protected $table = 'dv_links';
	protected $guarded = array();
	public $timestamps = false;
	public static function saveIco( $image ){

		$filename  = time() . '.' . $image->getClientOriginalExtension();

		$img = Image::make($image->getRealPath());
		$img->save( public_path('uploads/ico/' . $filename) );

		return $filename;
	}

}