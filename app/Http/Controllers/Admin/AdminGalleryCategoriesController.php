<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\GalleryRequest;

use App\Models\GalleryCategories;
use App\Models\Gallery;
use File;
class AdminGalleryCategoriesController extends AdminBaseController
{
    static protected $title = 'Галерея';

    //Отображение основной страницы линков
    public function getIndex(){
        $title = self::$title;
        $gallery_cat = GalleryCategories::all();

        return view('admin.showGalleryCategories', compact(['title', 'gallery_cat']));
    }

    //Отображение страницы редактирования -> редактирование
    public function getEdit($id){
        $title = self::$title;
        $gallery_cat  = GalleryCategories::where('id', '=', $id)->first();

        return view('admin.editGalleryCategories', compact(['gallery_cat', 'title']));
    }

    //Отображение страницы редактирования -> добавление
    public function getAdd(){
        $title = self::$title;
        $gallery_cat = new GalleryCategories;
        $gallery_cat->img = '';

        return view('admin.editGalleryCategories', compact(['title', 'gallery_cat']) );
    }

    //Добавление данных
    public function postAdd(GalleryRequest $request){
        $img = $request->file('img');
        $img_id = null;
        if ($img) { $img_id = GalleryCategories::saveImg('gallery', $img); } 

        $insert = array_merge( $request->except( ['img', '_token'] ), ['img' => $img_id] );
        $gallery_cat = GalleryCategories::create( $insert );

        return redirect('/master/gallery-categories/edit/'.$gallery_cat->id );
    }

    //Редактирование данных
    public function postEdit(Request $request, $id){

        $reqest_all = $request->all() ;
        if( $img = $request->file('img') )
        {
            if (!is_null(GalleryCategories::find($id)->img))
                File::delete( public_path('uploads/gallery/'.GalleryCategories::find($id)->img ));
           
            $img_id = GalleryCategories::saveImg('gallery', $img);
            GalleryCategories::where('id', '=', $id)->update(['img' => $img_id]);
        }
       
        
        GalleryCategories::where('id', '=', $id)->update(array_merge( $request->except( ['img', '_token'] ) ) );
        return redirect()->back()->with('success', 'Клиент успешно обновлен!');
    }

    //Удаление
    public function postIndex(Request $request) {
        
        $id = $request->get('check')[0];
        // print_r($id); exit();
        if (!is_null(GalleryCategories::find($id)->img))
                File::delete( public_path('uploads/gallery/'.GalleryCategories::find($id)->img ));
        // удалим из бд
        GalleryCategories::destroy( $id );
        $ids[] = ''; 
        $g_ids = Gallery::where('cat_id', '=', $id)->get();
        foreach ($g_ids as $key) {
            $ids[] = $key->id;
        }
        Gallery::destroy( $ids );

        return redirect()->back();
    }
}
