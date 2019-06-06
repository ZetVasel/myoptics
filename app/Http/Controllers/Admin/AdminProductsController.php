<?php

namespace App\Http\Controllers\Admin;

use App\Models\Charlist;
use App\Models\CharlistOptions;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\AdminBaseController;

use App\Models\Products;
use App\Models\Category;
use App\Models\ProductsCategories;
use App\Models\Brands;
use App\Models\Features;
use App\Models\Options;

use App\Http\Requests\ProductsRequest;
use App\Http\Requests\AddProductRequest;
use App\Http\Requests\ProductPhotosRequest;

use Session;
use Input;
use Response;

class AdminProductsController extends AdminBaseController
{

	public function productsManagement(){

		$title = 'Управление товаром';
		return view('admin.showProductsTypes', compact('title'));
	}



    public function getIndex(){

    	$title = "Продукты";
		$products_count = Products::count();


		// $products = Products::orderBy('id');
		$products = new Products;



		if( $category_id = Input::get('category') ){
			if($category_id == 'all'){
				// $products = Products::orderBy('id');
			}
			else{
				$products = $products->where(function($query) use ($category_id){

					$query->whereIn('dv_products.id', function($q) use ($category_id){
						$q->select('dv_products_categories.product_id')
						->from('dv_products_categories')
						->where('dv_products_categories.category_id', '=', $category_id)
						->get();
					});
				});
			}

		}
		if( $brand_id = Input::get('brand') ){
			if($brand_id == 'all'){
				// $products = $products->orderBy('id');
			}
			else{
				$products = $products->whereBrand_id($brand_id);
			}
		}
		if( $product_id = Input::get('product') ){
			if($product_id == 'all'){
				// $products = $products->orderBy('id');
			}
			else{
				$products = $products->whereId($product_id);
			}
		}
		if( $mark_id = Input::get('mark') ){
			if($mark_id == 'all'){
				// $products = $products->orderBy('id');
			}
			elseif($mark_id == 'sale'){
				$products = $products->whereSale(1);
			}
			elseif($mark_id == 'share'){
				$products = $products->whereShare(1);
			}
			elseif($mark_id == 'hit'){
				$products = $products->whereHit(1);
			}
			elseif($mark_id == 'promo'){
				$products = $products->wherePromo(1);
			}			
		}

		if( $sorting_id = Input::get('sorting') ){
			if($sorting_id == 'id')
				$products = $products->orderBy('id');
			if ($sorting_id  == 'ASC_price')
				$products = $products->orderBy('price', 'ASC');
			if ($sorting_id  == 'DESC_price')
				$products = $products->orderBy('price', 'DESC');
			if ($sorting_id  == 'ASC_abc')
				$products = $products->orderBy('name', 'ASC');
			// print_r("expression");exit;
		}


		$products = $products->paginate(15);
		
		$categories = Category::getNestedList('name', null, '&nbsp;&nbsp;&nbsp;');
		$categories = array( 'all' => 'Все') + $categories;


		$products_all = Products::all();
		foreach ($products_all as $prod) {
			$products_search[$prod->id] = $prod->name;
		}
		 if( count($products) > 0 )
			$products_search = array( 'all' => 'Все') + $products_search;
		 else $products_search[] = '';

		$marks = array(	'all' => 'Все',
						'share' => 'Акция',
						'sale' => 'Распродажа',
						'hit' => 'Хит продаж'
						);
		$sortings = array(	'id' => 'По-умолчанию',
							'ASC_price' => 'от дешевых к дорогим',
							'DESC_price' => 'от дорогих к дешевым',
							'ASC_abc' => 'по алфавиту'
							);
		if( $prod_page = Input::get('page') )
			Session::put('prod_page', $prod_page );

    	return view('admin.showProducts', compact(['title', 'products', 'products_search', 'products_count', 'categories',  'marks', 'sortings']));
    }


	public function postIndex( Request $request ) {

		// ajax
		if( $request->ajax() ) {

			if( $request->has('sale') ){
				Products::where( 'id', '=', $request->get('id') )->update( ['sale' => $request->get('sale')] );
                Products::where( 'id', '=', $request->get('id') )->update( ['new' => 0] );
                Products::where( 'id', '=', $request->get('id') )->update( ['discount' => 0] );
            }
			if( $request->has('discount') ) {
                Products::where('id', '=', $request->get('id'))->update(['discount' => $request->get('discount')]);
                Products::where( 'id', '=', $request->get('id') )->update( ['sale' => 0] );
                Products::where( 'id', '=', $request->get('id') )->update( ['new' => 0] );
            }
			if( $request->has('new') ) {
                Products::where('id', '=', $request->get('id'))->update(['new' => $request->get('new')]);
                Products::where('id', '=', $request->get('id'))->update(['discount' => 0]);
                Products::where( 'id', '=', $request->get('id') )->update( ['sale' => 0] );
            }
			if( $request->has('wholesale') )
				Products::where( 'id', '=', $request->get('id') )->update( ['wholesale' => $request->get('wholesale')] );
			if( $request->has('retail') )
				Products::where( 'id', '=', $request->get('id') )->update( ['retail' => $request->get('retail')] );			
			if( $request->has('in_stock') )
				Products::where( 'id', '=', $request->get('id') )->update( ['in_stock' => $request->get('in_stock')] );
			if( $request->has('main_img') )
				Products::where( 'id', '=', $request->get('id') )->update( ['main_img' => $request->get('main_img')] );
			return Response::json( 1 );
		}

		$ids = $request->get('check');
		// если действие для удаления
		if( $request->get('action') == 'delete' ) {
			// удалим из бд
			Products::destroy( $ids );
		}
		return redirect()->back();
	}


	public function getAdd(){

		$post = new Products;
		$post->image = '';
		$title = 'Добавление продукта';

		$prod_page = 1;

		$categories = Category::all();

		$features = Features::with('variants')->get();
        $charlist = Charlist::with('variants')->get();
		$alike_prod = Products::all();
		$post->alike =[];


		$post->categories = [];

		return view('admin.editProductsPost', compact(['post','title', 'categories','prod_page', 'features', 'alike_prod', 'charlist']));
	}


	public function postAdd( AddProductRequest $request ){

		$alike_prod = $request->has('alike') ? $request->get('alike') : [];
		$input = array_merge( $request->except('categories', 'feature', 'row_table', 'alike', 'charlist'), ['alike' => serialize($alike_prod)] );
		$post = Products::create( $input );

		if( $categories = $request->get('categories') )
			ProductsCategories::saveCategories( $post->id, $categories );	
		if( $feature = $request->get('feature') )
			Options::saveOptions( $post->id, $feature );

        if( $charlist = $request->get('charlist') )
            CharlistOptions::saveOptions( $post->id, $charlist );



		$insert = Products::descrTablesInfo($request->get('row_table') );
			Products::whereId($post->id)->update( ['specification' => serialize( $insert )] );
		return redirect('master/products/edit/'.$post->id)->with('success', 'Продукт успешно добавлен!');
	}


	public function getEdit( $id ){

		$post = Products::find($id);
		$title = 'Редактирование продукта';
		$categories = Category::all();

		$features = Features::with('variants')->get();
        $options = Options::getOptions( $id );

        $charlist = Charlist::with('variants')->get();
		$charlist_options = CharlistOptions::getOptions( $id );

		if(Session::has('prod_page'))
			$prod_page = Session::get('prod_page');
		else
			$prod_page = 1;

		$post->categories = ProductsCategories::getCategories($id);


		$post->specification = unserialize($post->specification);
		$alike_prod = Products::where('id', '!=', $post->id)->get();
		$post->alike = ( $post->alike != '' ) ? unserialize($post->alike) : [];


		return view('admin.editProductsPost', compact(['post', 'title', 'categories', 'prod_page', 'features', 'options', 'alike_prod', 'charlist', 'charlist_options']));
	}


	public function postEdit( Request $request, $id ){


        $alike_prod = $request->has('alike') ? $request->get('alike') : [];
        $input = array_merge( $request->except('categories', 'feature', 'row_table', 'charlist'), ['alike' => serialize($alike_prod)] );

        $products = Products::findOrFail($id)->update( $input );

		if( $categories = $request->get('categories') ){
			ProductsCategories::saveCategories( $id, $categories );
		}

        if( $feature = $request->get('feature') ) {
            Options::saveOptions($id, $feature);
        }

//        print_r( $request->get('charlist'));exit();
            CharlistOptions::saveOptions( $id, $request->get('charlist') );




        $insert = Products::descrTablesInfo($request->get('row_table') );
			Products::whereId($id)->update( ['specification' => serialize( $insert )] );	
		return redirect('/master/products/edit/' . $id)->with('success', 'Продукт успешно обновлен!');
	}


	public function getImage( $id ){

		$post = Products::find($id);
		$title = 'Изображения продукта - '.$post->name;

		if( $post->imgs != '' )
			$post->imgs = unserialize($post->imgs);
		else{
			$post->imgs = [];
		}

		return view('admin.showProductPhotos', compact(['post', 'title']));
	}



	public function postImage( Request $request, $id ){

		// если действие для удаления
		if( $request->get('action') == 'delete' ) {
			
			$keys = $request->get('check');
			// удалим из бд
			Products::removeImgs( $id, $keys );
		}

		// если загрузка изображений
		if( $request->file('image')[0] ){
			Products::saveImgs( $request, $id );
		}

		return redirect()->back();
	}


}
