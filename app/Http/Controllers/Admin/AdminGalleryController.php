<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AdminBaseController;

use App\Http\Requests\GalleryRequest;

use App\Models\Gallery;

class AdminGalleryController extends AdminBaseController
{
    public function getIndex($id){

        $title = 'Галерея';
        $slides = Gallery::where('cat_id', '=', $id)->paginate(12);

        return view('admin.showGallery', compact(['title', 'slides']));
    }

    public function postIndex( Request $request, $id )
    {
        if( $image = $request->file('img') ){
            $img_id = Gallery::saveImg( 'gallery', $image );
            $insert = array_merge( $request->except( ['img', '_token', 'cat_id'] ), ['img' => $img_id], ['cat_id' => $id] );
            $gallery_cat = Gallery::create( $insert );
        }
        // удалим из бд
        if( $request->get('action') == 'delete' ) {
            $ids = $request->get('check');
            Gallery::destroy( $ids );
        }
        return redirect()->back();
    }
}
