<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Image;
use File;

class Gallery extends Model
{
	protected $table = 'dv_gallery';
	protected $guarded = array();
	public $timestamps = false;	
	
	public static function saveImg( $foolder, $image ){
		$filename  = time() . '.' . $image->getClientOriginalExtension();

		$img = Image::make($image->getRealPath());
		$img->save( public_path('uploads/'.$foolder.'/' . $filename) );

		return $filename;
	}

	public static function destroyImg( $foolder, $id ){
		File::delete( public_path('uploads/'.$foolder.'/'.$id) );
		
		return true;
	}
	public static function destroy( $ids ){
		// print_r($ids); exit;
		$slides = self::whereIn('id', $ids)->get();
		foreach( $slides as $sl )
			File::delete( public_path('uploads/gallery/'.$sl->img) );

		parent::destroy( $ids );
	}	
}
