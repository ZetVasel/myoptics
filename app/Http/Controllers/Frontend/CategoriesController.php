<?php

namespace App\Http\Controllers\Frontend;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Frontend\BaseController;

use App\Models\Page;

use App\Models\ProductsCategories;
use App\Models\Settings;
use App\Models\Category;
use App\Models\Products;
use App\Models\Options;
use App\Models\Features;

use Input;
use Session;
use DB;
use Auth;

class CategoriesController extends BaseController{

	public function getCategories(){
		$page = Page::whereSlug( 'categories' )->firstOrFail();
		$categories = Category::where("depth", "==", "0")->get();

		return view('frontend.showCategories', compact(['page', 'categories']));
	}

    public function getOpenCat(){
    	return redirect('/category/'.Category::first()->slug);
    }

	public function getProducts(Request $request,  $slug ){

        if($slug == 'allseen'){

            $breadcrumb = new Category;
            $breadcrumb->name = 'Последние просмотренные продукты';
            $breadcrumb->slug = 'allseen';
            $breadcrumbs[0] = $breadcrumb;


            $seeProduct = Session::get('seeProduct');
            $page = new Page;
            $page->name = $page->meta_title = 'Последние просмотренные продукты';
            if(count($seeProduct)> 0){
                $ids = $seeProduct;
                $products = Products::whereIn('id', $ids);
                $products = $products->paginate(12);
                $products = Products::refImg($products);

            }else{
                $products = [];
            }

            return view('frontend.showAllSeen', compact(['products','page', 'lastSeenProducts', 'breadcrumbs']));


        }



		$page = Category::whereSlug( $slug )->first();
		$breadcrumbs = $page->ancestorsAndSelf()->get();
		if( $page->isRoot() )
			$parent_cat = $page->descendants()->get();
		else
			$parent_cat = $page->siblingsAndSelf()->get();

		$child_ids = [];
		$childs = $page->descendantsAndSelf()->get();
		foreach($childs as $ch){
			// id текущей категории и всех вложенных
			$child_ids[] = $ch->id;
		}



         $userDiscount = 0;

        $settings = Settings::first();
        if (Auth::check()) {
            $user = Auth::user();

            if(User::getOrderedSum($user->id) >= $settings->sum){
                //коефициент скидки
                $userDiscount = $settings->discount / 100;
            }
        }

		// print_r($child_ids);exit();
		$products = Products::Join('dv_products_categories', 'dv_products.id', '=', 'dv_products_categories.product_id')
							->select('dv_products.*')
							->whereIn( 'dv_products_categories.category_id', $child_ids )
							->where( function($query) {
								// фильтрация при выборе продуктов
								if( $filter = Input::get('filter') ) {
									foreach( $filter as $feature_id => $value ) {
										$query->whereIn( 'dv_products.id', function($q) use ( $feature_id, $value ) {
										 $q->select('dv_options.product_id')
										 ->from('dv_options')
										 ->where( 'dv_options.feature_id', $feature_id )
										 ->whereIn( 'dv_options.value', $value );
										});
									}
								}
								if( Input::has('priceMin') && Input::has('priceMax') ) {

                                    $userDiscount = 0;

                                    $settings = Settings::first();
                                    if (Auth::check()) {
                                        $user = Auth::user();

                                        if(User::getOrderedSum($user->id) >= $settings->sum){
                                            //коефициент скидки
                                            $userDiscount = $settings->discount / 100;
                                        }
                                    }

									$priceMin = Input::get('priceMin') / (1 - $userDiscount);
									$priceMax = Input::get('priceMax') / (1 - $userDiscount);

									$query->whereIn( 'dv_products.id', function($q) use ($priceMin,$priceMax) {
										$q->select('dv_products.id')
										  ->from('dv_products')
										  ->whereRaw('dv_products.price BETWEEN '.$priceMin.' AND ' .$priceMax );
									});
								}
							})->groupBy('dv_products.id');


		//фильтрация по GET параметрам					
		$products = self::filtersInputs( $products );

    	$per_page_opt = [12 ,18,27, 36, 72];
    	$per_page = !Input::has('per_page') ? $per_page_opt[0] : Input::get('per_page');

    	$count_all_products = count($products->get());

		$products = $products->paginate($per_page);


		// фото продукта
		$products = Products::refImg( $products );
	    // фильтры для товаров
		$features = self::selectFeatures( $child_ids, $products );

// print_r($features);exit;

		if( Session::get('product_toggle') ) $toggle = Session::get('product_toggle') ;
			else $toggle = 0;


		$price_range = self::priceRange( $child_ids );
		$price_range['max'] = ceil($price_range['max']) - (ceil($price_range['max']) * $userDiscount);
		$price_range['min'] = floor($price_range['min']) - (floor($price_range['min']) * $userDiscount);
		$cur_price['min'] = Input::has('priceMin') ? Input::get('priceMin') : $price_range['min'];
		$cur_price['max'] = Input::has('priceMax') ? Input::get('priceMax') : $price_range['max'];


		 if(Input::has('page')){
		 	Input::get('page') != 1 ? $now_page_num = intval(Input::get('page')) : $now_page_num = 1; //текущая страница

		 }else{
		 	$now_page_num = 1;
		 }

	    $pages_count = round($count_all_products/$per_page);//оличество страниц всего
	    $now_page_num > $pages_count ? $now_page_num = $pages_count : 
	    													$now_page_num > 1 ?: $now_page_num = 1;

	   	$next = ($now_page_num + 1) <= $pages_count ? $now_page_num + 1 : null;
	   	$prev = ($now_page_num - 1) >= 1 ? $now_page_num - 1 : null;
	   	$url = explode('?', $_SERVER['REQUEST_URI']);
	   	$url = $url[0];


	   	$seenArray = Session::get('seeProduct');

	   	if(is_array($seenArray)){
	   	    $lastSeenProducts = Products::whereIn('id', $seenArray)->get();
            $lastSeenProducts = Products::refImg( $lastSeenProducts );
        }


		return view('frontend.showProducts', compact(['breadcrumbs', 'parent_cat', 'page', 'products',  'features', 'toggle', 'per_page_opt', 'cur_price', 'price_range', 'next' , 'prev', 'url', 'lastSeenProducts']));

	}

	
	//фильтры для категорий товаров
	private function selectFeatures( $child_ids, $products  ){
		$features = Features::groupBy('id')->orderBy('name')->get();

		// $features = Features::groupBy('id')->orderBy('name')
		// 	->with(['options' => function($query) use ($child_ids)  {

		// 		$query->join('dv_products_categories', 'dv_options.product_id', '=', 'dv_products_categories.product_id')
		// 			  ->whereIn( 'dv_products_categories.category_id', $child_ids )
		// 			  ->selectRaw('dv_options.*, COUNT(dv_options.product_id) AS count')
		// 			  ->groupBy('dv_options.value')
		// 			  ->orderBy('dv_options.value', 'DESC');
		// 	}])->get();


		foreach ($features as $key => $value) {
			$options = Options::join('dv_products_categories', 'dv_options.product_id', '=', 'dv_products_categories.product_id')
					  ->whereIn( 'dv_products_categories.category_id', $child_ids )
					  ->where( 'dv_options.feature_id', $value->id )
					  // ->selectRaw('dv_options.*, COUNT(dv_options.product_id) AS count')
					  ->groupBy('dv_options.value')
					  ->orderBy('dv_options.value', 'DESC')
					  ->get();
				$count = 0;
				$productsArray = Products::join('dv_products_categories', 'dv_products.id', '=', 'dv_products_categories.product_id')
									->whereIn( 'dv_products_categories.category_id', $child_ids )
									->select( 'dv_products.id' )
									->groupBy( 'dv_products.id' )
							  		->get();

				$prodIds = array();

				if(count($productsArray)){
					foreach ($productsArray as $item) {
						$prodIds[] = $item->id;
					}
				}

			if(count($options)){
				foreach ($options as $key2 => $value2) {
					$count = Options::whereIn('product_id', $prodIds)
					  		->where( 'feature_id', $value->id )
					  		->where( 'value', $value2->value )
					  		->groupBy( 'product_id' )
					  		->get(); 		
					$options[$key2]->count = count($count);  		
				}
			}

			$features[$key]->options = $options;


		}

		return $features;
	}

	private  function filtersInputs( $products){
		if(Input::has('sorter') && Input::get('sorter') != "all"){
			if (Input::get('sorter') == 'ASC_price')
				$products = $products->orderBy('dv_products.price', 'ASC');
			if (Input::get('sorter') == 'DESC_price')
				$products = $products->orderBy('dv_products.price', 'DESC');
			if (Input::get('sorter') == 'ASC_abc')
				$products = $products->orderBy('dv_products.name', 'ASC');
			if (Input::get('sorter') == 'ASC_cba')
				$products = $products->orderBy('dv_products.name', 'DESC');
		}
		return $products;
	}

	// Диапазон цен в фильтре
	private function priceRange( $child_ids ){

		$price_range = Products::leftJoin('dv_products_categories', 'dv_products.id', '=', 'dv_products_categories.product_id')
										->whereIn( 'dv_products_categories.category_id', $child_ids )
										->selectRaw('max(dv_products.price ) as max, min(dv_products.price) as min')
										->first();
		return 	$price_range;	
	}

    // изменяет toggle
    public function changeToggle( Request $request ){
    	Session::put( 'product_toggle', $request->get('toggle') );
    	return 1;
    }
}