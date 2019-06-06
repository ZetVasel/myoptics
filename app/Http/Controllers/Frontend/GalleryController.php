<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\BaseController;

use App\Models\Page;
use App\Models\Gallery;
use App\Models\GalleryCategories;

class GalleryController extends BaseController
{
    public function index(){

        $page = Page::whereSlug('gallery')->firstOrFail();
        $gallery_cat = GalleryCategories::all();

        return view('frontend.showGallery', compact(['gallery_cat', 'page']));
    }


    public function gallery( $id ){
        $page = Page::whereSlug('gallery')->firstOrFail();
        $gallery = Gallery::where( 'cat_id', '=', $id )->get();

        return view('frontend.showGallery', compact(['gallery', 'page']));
    }
}
