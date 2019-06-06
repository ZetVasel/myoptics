<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Products;


class ProductsCategories extends Model
{
	protected $table = 'dv_products_categories';
	protected $guarded = [];
	public $timestamps = false;


	public static function getCategories( $id ){

		$categories_all = ProductsCategories::whereProduct_id( $id )->get();
		$categories = [];
		foreach( $categories_all as $category )
			$categories[$category->category_id] = $category->category_id;

		return $categories;
	}


	// сохраняет опции товара
	public static function saveCategories( $id, $category ){

		ProductsCategories::whereProduct_id( $id )->delete();

		foreach ($category as $cat_id) {
			$for_insert[] = ['category_id' => $cat_id, 'product_id' => $id];
		}

		if( isset( $for_insert ) )
			ProductsCategories::insert( $for_insert );
	}
	
	public static function getProducts($id){
		$products_all = ProductsCategories::whereCategory_id( $id )->get();
		$products = [];
		foreach ( $products_all as $product) {
			$products[] = Products::whereId($product->category_id)->get();
		}
		return $products;
	}

}
