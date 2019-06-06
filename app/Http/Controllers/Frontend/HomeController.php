<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Frontend\BaseController;
use Session;

use App\Models\Page;
use App\Models\Settings;
use App\Models\Slider;
use App\Models\News;
use App\Models\Category;
use App\Models\Products;
use App\Models\Brands;

class HomeController extends BaseController{

    public function index(){

    	$page = Page::whereSlug('/')->first();
        $brands = Brands::all();
    	$news = News::orderBy('created_at', 'DESC')->get();
      	$slider = Slider::all();



//      	print_r($news);exit();


        //время смены слайдов
        $slideChangeTime = Settings::first()->delay == 0 ? Settings::first()->delay : Settings::first()->delay * 1000;


        //catBlock 1
        $catBlock1 = Category::where('show_block_1', 1)->first();
        if($catBlock1){
            $child_ids = [];
            $childs = $catBlock1->descendantsAndSelf()->get();
            foreach($childs as $ch){
                // id текущей категории и всех вложенных
                $child_ids[] = $ch->id;
            }

            $catBlock1Products =  Products::Join('dv_products_categories', 'dv_products.id', '=', 'dv_products_categories.product_id')
                ->select('dv_products.*')
                ->whereIn( 'dv_products_categories.category_id', $child_ids )
                ->groupBy('dv_products.id')
                ->get();

            $catBlock1Products = Products::refImg($catBlock1Products);

        }


        //catBlock 2
        $catBlock2 = Category::where('show_block_2', 1)->first();

        if($catBlock2){
            $child_ids = [];
            $childs = $catBlock2->descendantsAndSelf()->get();
            foreach($childs as $ch){
                // id текущей категории и всех вложенных
                $child_ids[] = $ch->id;
            }

            $catBlock2Products =  Products::Join('dv_products_categories', 'dv_products.id', '=', 'dv_products_categories.product_id')
                ->select('dv_products.*')
                ->whereIn( 'dv_products_categories.category_id', $child_ids )
                ->groupBy('dv_products.id')
                ->get();

            $catBlock2Products = Products::refImg($catBlock2Products);

        }



        //catBlock 3
        $catBlock3 = Category::where('show_block_3', 1)->first();

        if($catBlock3){
            $child_ids = [];
            $childs = $catBlock3->descendantsAndSelf()->get();
            foreach($childs as $ch){
                // id текущей категории и всех вложенных
                $child_ids[] = $ch->id;
            }

            $catBlock3Products =  Products::Join('dv_products_categories', 'dv_products.id', '=', 'dv_products_categories.product_id')
                ->select('dv_products.*')
                ->whereIn( 'dv_products_categories.category_id', $child_ids )
                ->groupBy('dv_products.id')
                ->get();

            $catBlock3Products = Products::refImg($catBlock3Products);
        }



      return view('frontend.home', compact(['page', 'slider', 'brands', 'news', 'slideChangeTime', 'catBlock1', 'catBlock2', 'catBlock3', 'catBlock1Products', 'catBlock2Products', 'catBlock3Products']));
    }


}
