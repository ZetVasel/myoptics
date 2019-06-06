<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Frontend\BaseController;
use App\Models\Page;
use App\Models\Articles;

class ArticlesController extends BaseController
{
    public function index(){

        $page = Page::whereSlug('articles')->first();
        $docs = Articles::latest()->paginate(10);
        return view('frontend.showDocs', compact(['docs', 'page']));
    }


    public function articlesPost( $slug ){

        $page = Articles::whereSlug( $slug )->firstOrFail();
        $parent = Page::whereSlug('articles')->first();

        return view('frontend.showDocItem', compact(['page','parent']));
    }
}
