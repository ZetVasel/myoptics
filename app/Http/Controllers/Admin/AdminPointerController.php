<?php
/**
 * Created by PhpStorm.
 * User: Dvacom
 * Date: 30.05.2017
 * Time: 14:12
 */

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;

use App\Models\Pointer;

class AdminPointerController extends AdminBaseController
{
    public function getIndex(){
        $title = 'Маркеры';
        $pointers_count = Pointer::count();
        $pointers = Pointer::latest()->paginate(15);
        return view('admin.showPointers', compact(['pointers', 'pointers_count', 'title']));
    }


    public function postIndex( Request $request ) {

        $ids = $request->get('check');
        // если действие для удаления
        if( $request->get('action') == 'delete' ) {
            // удалим из бд
            Pointer::destroy( $ids );
        }
        return redirect()->back();
    }


    public function postAdd( Request $request )
    {
        $post = Pointer::create( $request->except('_token') );

        return redirect('master/pointers')->with('message', 'Маркер успешно добавлен!');
    }


    public function postEdit( Request $request, $id )
    {
        $news = Pointer::findOrFail($id);
        $news->update( $request->except('_token') );

        return redirect('/master/pointers/edit/' . $id)->with('message', 'Маркер успешно обновлен!');
    }

    public function getAdd()
    {
        $post = new Pointer;
        $title = 'Создать маркер';
        return view('admin.editPointerPost', compact(['post','title']));
    }


    public function getEdit( $id )
    {
        $post = Pointer::find($id);
        $title = 'Редактирование маркера';
        return view('admin.editPointerPost', compact(['post', 'title']));
    }


}