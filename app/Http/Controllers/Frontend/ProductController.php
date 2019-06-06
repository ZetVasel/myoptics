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
use App\Models\Offers;

use App\Models\Charlist;
use App\Models\CharlistOptions;

use Session;
use Auth;

class ProductController extends BaseController
{
	public function showProduct( $slug ){

		$page = Products::whereSlug( $slug )->firstOrFail();
		$page->specification = unserialize($page->specification);
		$breadcrumbs = Category::get_parent_categories( ProductsCategories::where('product_id', '=', $page->id)->pluck('category_id'));

		if( $page->imgs != '' ){
			$page->imgs = unserialize($page->imgs);
			$page->image = $page->imgs[$page->main_img];
		}
		else $page->image = null;

		$features = Options::whereProductId($page->id)->get();
		$prod_reviews = ProductReviews::whereProductId($page->id)->whereVisible(1)->orderBy('id', 'DESC')->get();


//		print_r($prod_reviews);exit();


		$alike_pr = Products::whereIn('id', unserialize($page->alike))->get();
		$alike_pr = Products::refImg( $alike_pr );
		// $offers = Offers::whereProductId($page->id)->first();
		$offers = Offers::join('dv_offer_products', 'dv_offers.id', '=', 'dv_offer_products.offer_id')
						->where('dv_offer_products.product_id','=',$page->id)
						->select('dv_offers.*')
						->first();
		// print_r($alike_pr);exit();


        $charlist = Charlist::with('variants')->get();
        $charlist_options = CharlistOptions::getOptions( $page->id );

        //последние просмотреные
        $seeProduct = Session::get('seeProduct');
        Session::put('seeProduct', Products::toSeeProduct($seeProduct, $page->id));



		return view('frontend.showProduct', compact(['page', 'alike_pr', 'breadcrumbs', 'prod_reviews', 'features', 'offers', 'charlist', 'charlist_options']));
	}

	// добавление отзыва к продукту 
	public function postAddReviews( AddProductReviewRequest $request ){
		$review = ProductReviews::create( $request->all() );
		return redirect()->back()->with( 'rev_message', 'Ваш отзыв успешно добавлен и будет отображен после проверки администратором');
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
		else
			return 'Данный товар уже был добавлен в сравнение ранее';
	}


	// показ страницы сравнения
	public function showCompare(){

		$page = Page::whereSlug('compare')->first();
		$compare_ids = Session::has('compare') ? Session::get('compare') : [];

		$products = Products::whereIn('dv_products.id', $compare_ids)
							->take(10)
							->groupBy('dv_products.id')
							->get();

		// фото продукта
		$products = Products::refImg( $products );

		return view('frontend.showCompare', compact('page', 'products'));
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
