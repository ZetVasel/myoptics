<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Frontend\BaseController;

use App\Http\Requests;

use App\Models\Page;
use App\Models\Settings;
use App\Models\Products;
use App\Models\Brands;
use App\Models\Pointer;


use Input;
use Session;

class PageController extends BaseController
{
	public function index( $page_url ){
		$page = Page::whereSlug( $page_url )->firstOrFail();

		return view('frontend.showPage', compact(['page']));
	}

	public function getFavorites(){
		$page = Page::whereSlug( 'favorites' )->firstOrFail();
        // избранное
        if( Session::has('favorites') ){
            $favorites = Session::get('favorites');
            $products = Products::whereIn('id',  $favorites)->get();
            $products = Products::refImg($products);
        }
        else $favorites[] = null;   

        return view('frontend.showFavorites', compact(['page', 'products'])); 		
	}
    
    public function postFavorites( Request $request ){
        $favorites = Session::get('favorites');
        if(($key = array_search($request->pr_id, $favorites)) !== false) {
            unset($favorites[$key]);
            Session::put('favorites', $favorites);
        } 

        return 1;       
    } 

    public function getBrand( $page_url ){
        $page = Brands::whereSlug( $page_url )->firstOrFail();

        return view('frontend.showPage', compact(['page']));

    }
    public function getContacts(){
        $page = Page::whereId(40)->firstOrFail();

//        print_r($page);exit();
        $pointers = Pointer::all();

        $firstBlockCount = round(count($pointers) / 2);

        $firstBlockContent = [];
        $secondBlockContent = [];

        $count = 0;

        foreach ($pointers as $key => $pointer){
            $pointer->key = $key;
            if($count++ < $firstBlockCount){
                $firstBlockContent[] = $pointer;
            }else{
                $secondBlockContent[] = $pointer;
            }
        }


//        print_r($secondBlockContent);exit();

        return view('frontend.showContacts', compact(['page', 'firstBlockContent', 'secondBlockContent', 'pointers']));

    }
}
