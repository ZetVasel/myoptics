<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Frontend\BaseController;
use App\Models\Brands;
use App\Models\Products;
use Input;
use Session;

class BrandsController extends BaseController
{
    public function getPost( $slug ){

    	$page = Brands::whereSlug( $slug )->firstOrFail();
    	$brands_all = Brands::where('image', '!=', '')->get();

    	// количество записей на странице
    	$per_page_opt = [12,24,48];

		if( $per_page = Input::has('per_page') ? Input::get('per_page') : Session::get('per_page'))
			Session::set('per_page', $per_page);

    	if( !Session::has('per_page') ){
    		$per_page = $per_page_opt[0];
    		Session::set('per_page', $per_page);
    	}

		$viewed = Session::has('viewed') ? Session::get('viewed') : [];

		$viewed_pr = Products::leftJoin('dv_categories', 'dv_products.category_id', '=', 'dv_categories.id')
							->select(['dv_products.*', 'dv_categories.name AS cat_name'])
							->whereIn('dv_products.id', array_reverse($viewed))
							->where('dv_products.imgs', '!=', '')
							->take(3)
							->get();

		// сортировка товара
		$sort_by_var = ['popular'=>'Самые популярные', 'pr_min_max' => 'Цена по возрастающей', 'pr_max_min' => 'Цена по убывающей'];
		$order = "id";
		$direction = 'DESC';
		if( $sort_by = Input::has('sort_by') ? Input::get('sort_by') : Session::get('sort_by') ){

			if( $sort_by == 'popular' ){
				$order = 'views';
				$direction = 'ASC';
			}
			elseif( $sort_by == 'pr_min_max' ){
				$order = 'price';
				$direction = 'ASC';
			}
			elseif( $sort_by == 'pr_max_min' ){
				$order = 'price';
				$direction = 'DESC';
			}
			Session::put('sort_by', $sort_by );
		}

    	$products = Products::whereBrandId($page->id)->orderBy( $order, $direction )->paginate($per_page);

    	return view('frontend.showBrand', compact(['page', 'products', 'per_page_opt', 'sort_by_var', 'brands_all', 'viewed_pr']));
    }
}
