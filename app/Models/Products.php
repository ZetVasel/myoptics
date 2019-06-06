<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

use App\Models\ProductsCategories;
use App\Models\Settings;
use Image;
use File;
use Session;
use Auth;

class Products extends Model
{
	protected $table = 'dv_products';
	protected $guarded = [];


	public function categories() {
		return $this->hasMany( 'App\Models\Category' , 'product_id' );
	}



	public static function toSeeProduct($seeId, $id){
        if(is_array($seeId)){
            if(array_search($id, $seeId) === false){
                $seeId[] = $id;
            }
        }else{
            $seeId[] = $id;
        }

        return $seeId;

    }


	public static function destroy( $ids ){

		$products = self::whereIn('id', $ids)->get();
		ProductsCategories::whereIn( 'product_id', $ids )->delete();


		foreach( $products as $prod ){

			$imgs = unserialize($prod->imgs);

			if( $imgs ){
				foreach( $imgs as $img ){
					File::delete( public_path() .'/uploads/products/lg/' . $img );
					File::delete( public_path() .'/uploads/products/sm/' . $img );
					File::delete( public_path() .'/uploads/products/md/' . $img );
				}
			}
		}
		parent::destroy($ids);
	}

	// сохранение изображения товаров
	public static function saveImgs( $request, $id ){

		$product = self::find( $id );

		foreach( $request->file('image') as $file ) {

			$filename = time() . '_'.$file->getClientOriginalName();
			$filename = self::transliterate($filename);
			$img = Image::make( $file );

			$img->widen(800)->save( 'uploads/products/lg/' . $filename, 100 );
			$img->fit(260, 190)->save( 'uploads/products/md/' . $filename, 100 ); // Попробывать загурзить фото !!!
			$img->widen(100)->save( 'uploads/products/sm/' . $filename, 100 );

			if( $product->imgs != '' ){
				$imgs = unserialize($product->imgs);
				$imgs[] = $filename;
			}
			else 
				$imgs[] = $filename;

			$product->update(['imgs' => serialize($imgs)]);
		}
	}

	// удаление изображения товаров
	public static function removeImgs( $id, $keys ){

		$product = self::find($id);
		$imgs = unserialize($product->imgs);

		if( count($keys) > 0 ){
			foreach( $imgs as $k => $img ){

				if( in_array( $k, $keys ) ){
					File::delete( public_path() .'/uploads/products/lg/' . $img );
					File::delete( public_path() .'/uploads/products/md/' . $img );
					File::delete( public_path() .'/uploads/products/sm/' . $img );
					unset($imgs[$k]);
				}
			}

			if( count($imgs) == 0 ){
				$imgs = '';
				$product->update(['main_img'=> 0]);
			}
			else{
				$imgs = array_values($imgs);
				$imgs = serialize($imgs);
			}

			$product->update(['imgs' => $imgs,'main_img'=> 0]);
		}		
	}

	// характеристики продукта
	public static function descrTablesInfo( $rows ){
		$insert = [];
			if($rows){
			$count = 0;
			foreach( $rows as $row ){
				if( $row[0] != '' && $row[1] != '' ){
					$insert[] = [ 'name' => $row[0], 'value' => $row[1] ];
					$count ++;
				}
			}
		}
		return $insert;
	}





	//проверка на сущетсование картинки в товаре при выводе
	public static function refImg( $products ){
		foreach ($products as $pr) {

				$images = unserialize($pr->imgs);
				if( $images != '' ){
				$pr->image = $images[$pr->main_img];
			}
			else{
				$pr->image = null;
			}
		}	
		return $products;
	}



	// считает и возвращает к-во и полную цену товаров в корзине
	public static function priceAndQuantity( $cur_ids ){

        $userDiscount = 0;
        if (Auth::check()){
            $user = Auth::user();
            $settings = Settings::first();
            if(User::getOrderedSum($user->id) >= $settings->sum){
                //коефициент скидки
                $userDiscount = $settings->discount / 100;
            }
        }

        $total_price = 0;
	    foreach ($cur_ids as $item){
	        foreach ($item as $key => $value){
	            $product = Products::whereId($key)->first();
                $total_price += round($product->price * $value['quantity'] - ($product->price * $value['quantity'] * $userDiscount));
            }
        }


		return ['total_price' => $total_price, 'quantity' => count($cur_ids)];
	}

	// добавляет товар в корзину или добавляет к-во, если товар уже там есть
	public static function addToCart( $cur_ids, $product_id, $single_quantity = null, $featured = null, $quantityLeft = null, $quantityRight = null, $featureleft = null, $featureright = null ){



        if(isset($single_quantity)){
            if(self::searchLiked($cur_ids, $product_id, $featured) !== false){

                $searchId = self::searchLiked($cur_ids, $product_id, $featured);

                $cur_ids[$searchId][$product_id]['quantity'] += $single_quantity;
            }else{
                $cur_ids[][$product_id] = [
                    'quantity' => $single_quantity,
                    'charlist' => $featured
                ];
            }
        }else{


            if(self::searchLiked($cur_ids, $product_id, $featureleft) !== false) {
                $searchId = self::searchLiked($cur_ids, $product_id, $featureleft);
                $cur_ids[$searchId][$product_id]['quantity'] += $quantityLeft;
            }else{
                $cur_ids[][$product_id] = [
                    'quantity' => $quantityLeft,
                    'charlist' => $featureleft
                ];
            }

            if(self::searchLiked($cur_ids, $product_id, $featureright) !== false) {
                $searchId = self::searchLiked($cur_ids, $product_id, $featureright);
                $cur_ids[$searchId][$product_id]['quantity'] += $quantityRight;
            }else {
                $cur_ids[][$product_id] = [
                    'quantity' => $quantityRight,
                    'charlist' => $featureright
                ];
            }
        }
        return $cur_ids;

	}

	//ищем есть ли в корзине продукты с такими же характеристиками и ид
	public static function searchLiked($cur_ids, $prod_id, $featured){
	    if(is_array($cur_ids) && count($cur_ids) > 0){
	        foreach ($cur_ids as $key => $item){
	            foreach ($item as $product_id => $value){
	                if($product_id == $prod_id){
	                    if(count(array_diff($value['charlist'], $featured)) == 0){
	                        return $key;
                        }
                    }
                }
            }
        }

        return false;
    }


	public static function getProduct($id){
		$product = self::find($id);
		return ['name' => $product->name,'slug' => $product->slug];
	}
	public static function cartProducts( $cur_ids ){

		$products = self::whereIn('id',array_keys($cur_ids))->get();

		return $products;
	}

	// Транслитерация строк.
	protected static function rus2translit($string) {
	    $converter = array(
	        'а' => 'a',   'б' => 'b',   'в' => 'v',
	        'г' => 'g',   'д' => 'd',   'е' => 'e',
	        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
	        'и' => 'i',   'й' => 'y',   'к' => 'k',
	        'л' => 'l',   'м' => 'm',   'н' => 'n',
	        'о' => 'o',   'п' => 'p',   'р' => 'r',
	        'с' => 's',   'т' => 't',   'у' => 'u',
	        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
	        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
	        'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
	        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
	        
	        'А' => 'A',   'Б' => 'B',   'В' => 'V',
	        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
	        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
	        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
	        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
	        'О' => 'O',   'П' => 'P',   'Р' => 'R',
	        'С' => 'S',   'Т' => 'T',   'У' => 'U',
	        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
	        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
	        'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
	        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
	    );
	    return strtr($string, $converter);
	}

	public static function transliterate($str) {
	    // переводим в транслит
	    $str = self::rus2translit($str);
	    // в нижний регистр
	    $str = strtolower($str);
	    // удаляем начальные и конечные '-'
	    $str = trim($str, "-");
	    return $str;
	}

}
