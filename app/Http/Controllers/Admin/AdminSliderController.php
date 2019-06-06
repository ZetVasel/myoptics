<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\SliderRequest;
use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Slider;

class AdminSliderController extends AdminBaseController
{
    public function getIndex()
    {
        $title = 'Cлайды';
        $slides = Slider::latest()->paginate(12);
        $slides_count = Slider::count();
        return view('admin.showSlides', compact(['title', 'slides', 'slides_count']));
    }

    public function postIndex( SliderRequest $request )
    {
        if( $image = $request->file('image') ){
            $id = Slider::saveSlide( $image );
            Slider::whereId($id)->update(array_merge($request->except(['_token', 'image'])));
        }

        return redirect()->back();
    }

    public function postEditInfo( Request $request ){
        
    }

    public function postDelete( Request $request )
    {
        // если действие для удаления
        if( $request->get('action') == 'delete' ) {
            
            $ids = $request->get('check');
            // удалим из бд
            Slider::destroy( $ids );
        }

        return redirect()->back();
    }

    public function postSlideText( Request $request ){
        Slider::whereId( $request->sl_id )->update( $request->except('_token', 'sl_id') );
        return 1;
    }
}
