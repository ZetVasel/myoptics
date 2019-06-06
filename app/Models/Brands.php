<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Image;
use File;

class Brands extends Model
{
	protected $table = 'dv_brands';
	protected $guarded = [];


	public function categories() 
	{
		return $this->hasMany('App\Models\BrandsCategories', 'brand_id');
	}

	public static function saveImage( $image, $id ) {

		$filename  = time() . '.' . $image->getClientOriginalExtension();

		$img = Image::make($image->getRealPath());





		if($img->width() > 120){
		    $img->resize(120, 50)->save( public_path('uploads/brands/' . $filename) );
        }else{
            $img->save( public_path('uploads/brands/' . $filename) );
        }

		self::find( $id )->update(['image' => $filename]);
	}


	public static function updateImage( $image, $id ){

		$old_filename = self::find( $id )->image;
		File::delete( public_path() .'/uploads/brands/' . $old_filename );

		self::saveImage( $image, $id );
	}


	public static function destroy( $ids ){

		$post = self::whereIn( 'id', $ids )->get();

		foreach( $post as $p )
			File::delete( public_path() .'/uploads/brands/' . $p->image );

		parent::destroy( $ids );
	}


	public static function saveCategories( $post, $categories ){

		$categories_insert = [];
		foreach( $categories as $value )
			$categories_insert[] = ['brand_id' => $post->id, 'category_id' => $value];

		self::find( $post->id )->categories()->createMany( $categories_insert );
		return 1;
	}


	public static function panelInfo(){

		$a_brands = self::orderBy('name')->get();
		$letters = [];
		$letters_groups = ['0-9'=>[], 'А-Я'=>[]];

		foreach( $a_brands as $br ){

			$first_letter = strtoupper(substr( $br->name, 0, 1 ));

			if( in_array( $first_letter, ['А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я']))
				array_push( $letters_groups['А-Я'], ['name' => $br->name, 'slug' => $br->slug] );
			elseif( in_array( $first_letter, ['0','1','2','3','4','5','6','7','8','9'] ) )
				array_push( $letters_groups['0-9'], ['name' => $br->name, 'slug' => $br->slug] );
			else{
				if( array_key_exists($first_letter, $letters) )
					array_push( $letters[$first_letter], ['name' => $br->name, 'slug' => $br->slug] );
				else
					$letters[$first_letter] = [['name' => $br->name, 'slug' => $br->slug]];
			}
		}

		$letters = array_merge($letters, $letters_groups);

		return ['all_brands' => $a_brands, 'letters' => $letters];
	}
}
