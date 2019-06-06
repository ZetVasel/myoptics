<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use File;
use Image;

class Offers extends Model
{
	protected $table = 'dv_offers';
	protected $guarded = [];

	public function offerproducts() {
		return $this->hasMany( 'App\Models\OfferProducts' , 'offer_id' );
	}

    public static function boot(){
        parent::boot();    
        // cause a delete of a product to cascade to children so they are also deleted
        static::deleted(function($pages)
        {
            $pages->offerproducts()->delete();
        });
    }

	public static function saveImage( $image, $id ) {

		$filename  = time() . '.' . $image->getClientOriginalExtension();

		$img = Image::make($image->getRealPath());
		$img->fit(360,210)->save( public_path('uploads/offers/' . $filename) );

		self::find( $id )->update(['image' => $filename]);
	}


	public static function updateImage( $image, $id ){

		$old_filename = self::find( $id )->image;
		File::delete( public_path() .'/uploads/offers/' . $old_filename );

		self::saveImage( $image, $id );
	}


	public static function destroy( $ids ){

		$post = self::whereIn( 'id', $ids )->get();

		foreach( $post as $p )
			File::delete( public_path() .'/uploads/offers/' . $p->image );

		parent::destroy( $ids );
	}	
}
