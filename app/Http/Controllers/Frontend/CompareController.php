<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Controllers\Frontend\BaseController;

use App\Http\Requests;
use App\Http\Requests\AddProductReviewRequest;
use App\Http\Requests\AddToCompareRequest;

use App\Models\Page;
use App\Models\Products;
use App\Models\Category;
use App\Models\Settings;
use App\Models\ProductsCategories;
use App\Models\ProductReviews;
use App\Models\Features;
use App\Models\Options;

use Session;
use Auth;

class CompareController extends BaseController
{
	public function showCompare(){
		$page = Page::whereSlug('compare')->first();
		$compare_ids = Session::has('compare') ? Session::get('compare') : [];

		$category = Category::leftjoin('dv_products_categories','dv_categories.id','=','dv_products_categories.category_id')
							->whereIn('dv_products_categories.product_id', $compare_ids )
							->groupBy('dv_products_categories.category_id')
							->select('dv_categories.*')
							->selectRaw('dv_products_categories.*, COUNT(dv_products_categories.product_id) AS count')							
							->get();
		//достаем продукты
		$products = Products::leftjoin('dv_products_categories','dv_products.id','=','dv_products_categories.product_id')
							->leftjoin('dv_categories','dv_products_categories.category_id','=','dv_categories.id')
							->whereIn('dv_products_categories.product_id',$compare_ids)
							->select(['dv_products.imgs', 'dv_products.name', 'dv_products.code','dv_products.id', 'dv_products.main_img','dv_products.price'])
							->get();
		//достаем параметры фильтрации

		$features = Features::leftjoin('dv_options','dv_features.id','=','dv_options.feature_id')
							->whereIn('dv_options.product_id',$compare_ids)
							->select('dv_features.*')
							->with(['options' => function($query) use ($compare_ids)  {
									$query->whereIn( 'dv_options.product_id', $compare_ids );
							}])
							->groupBy('dv_features.id')
							->get();
		// print_r($products);exit();
		$products = Products::refImg( $products );	
		$page_false = 0;
		return view('frontend.showCompare', compact('page', 'products','category','page_false', 'features'));
	}
	public function getCompare( $slug ){

		$page = Category::whereSlug( $slug )->first();

		$compare_ids = Session::has('compare') ? Session::get('compare') : [];

		$category = Category::leftjoin('dv_products_categories','dv_categories.id','=','dv_products_categories.category_id')
							->whereIn('dv_products_categories.product_id', $compare_ids )
							->groupBy('dv_products_categories.category_id')
							->select('dv_categories.*')
							->selectRaw('dv_products_categories.*, COUNT(dv_products_categories.product_id) AS count')
							->get();

		$products = Products::leftjoin('dv_products_categories','dv_products.id','=','dv_products_categories.product_id')
							->leftjoin('dv_categories','dv_products_categories.category_id','=','dv_categories.id')
							->whereIn('dv_products_categories.product_id',$compare_ids)
							->where('dv_products_categories.category_id','=', $page->id)
							->select('dv_products.*')
							->get();

		//достаем параметры фильтрации
		$features = Features::leftjoin('dv_options','dv_features.id','=','dv_options.feature_id')
							->whereIn('dv_options.product_id',$compare_ids)
							->select('dv_features.*')
							->with(['options' => function($query) use ($compare_ids)  {
									$query->whereIn( 'dv_options.product_id', $compare_ids );
							}])
							->groupBy('dv_features.id')
							->get();
		$products = Products::refImg( $products );	
		$page_false = 0;
		return view('frontend.showCompare', compact('page', 'products','category','page_false', 'features'));
	}


	// добавление товаров в сравнение
	public function addToCompare( AddToCompareRequest $request ){

		$compare_ids = Session::has('compare') ? Session::get('compare') : [];

		// если нет такоего id в сессии - то добавим
		if( !in_array($request->get('product_id'), $compare_ids) ){

			$compare_ids[] = $request->get('product_id');
			Session::put('compare', $compare_ids);
			return 'Товар был добавлен в сравнение';
		}
		else{
			if(($key = array_search( $request->get('product_id'), $compare_ids) ) !== false) 
			    unset($compare_ids[$key]);
			Session::put('compare', $compare_ids );
			// return 'Данный товар уже был добавлен в сравнение ранее';
		}
	}

	// удалить товар из сравнения
	public function removeFromCompare( AddToCompareRequest $request ){

		$compare_ids = Session::get('compare');
		$cur_key = array_search( $request->get('product_id'), $compare_ids );
		array_splice($compare_ids, $cur_key, 1);
		Session::put('compare', $compare_ids );
		return;
	}

}
