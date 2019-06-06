<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Frontend\BaseController;
use App\Models\Page;
use App\Models\Offers;

class OffersController extends BaseController
{
    public function index(){

        $page = Page::whereSlug('offers')->first();
        $offers = Offers::latest()->paginate(10);
        return view('frontend.showOffers', compact(['offers', 'page']));
    }


    public function getOffers( $slug ){
    	$page = Offers::whereSlug( $slug )->firstOrFail();

        $products = Offers::join('dv_offer_products', 'dv_offers.id', '=', 'dv_offer_products.offer_id')
        				->join('dv_products', 'dv_offer_products.product_id', '=', 'dv_products.id')
        				->where('dv_offers.slug','=',$slug)
        				->select(['dv_products.*','dv_offers.id AS off_id'])
        				->groupBy('dv_products.id')
        				->get();

        foreach ($products as $pr) {
                $pr->price = round($pr->price, 2);
            if( $pr->imgs != '' ){
                $images = unserialize($pr->imgs);
                $pr->image = $images[$pr->main_img];
            }
            else{
                $pr->image = null;
            }
        }

        return view('frontend.showOffersPost', compact(['page','products']));
    }
}
