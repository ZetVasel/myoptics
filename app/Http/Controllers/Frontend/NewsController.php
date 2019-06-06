<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Frontend\BaseController;
use App\Models\Page;
use App\Models\News;

class NewsController extends BaseController
{
    public function index(){

        $page = Page::whereSlug('news')->first();
        $news = News::latest()->paginate(6);
        return view('frontend.showNews', compact(['news', 'page']));
    }


    public function getNews( $slug ){

        $page = News::whereSlug( $slug )->firstOrFail();

        return view('frontend.showPage', compact(['page']));
    }
}
