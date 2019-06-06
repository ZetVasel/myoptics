<?php
/**
 * Created by PhpStorm.
 * User: Dvacom
 * Date: 29.05.2017
 * Time: 10:27
 */

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\AdminBaseController;

use App\Models\Charlist;
use App\Models\CharlistOptionVariants;
use App\Models\CharlistOptions;


class AdminCharlistOptionVariantsController extends AdminBaseController
{

public function getShow( $id ){

    $feature = Charlist::find($id);
    $title = 'Значения параметра "'. $feature->name. ' "';
    $options = CharlistOptionVariants::whereCharlistId( $id )->paginate(15);

    return view('admin.showCharlistOptionVariants', compact(['title', 'options', 'id', 'feature']));
}


    public function postShow( $id, Request $request )
    {
        $ids = $request->get('check');
        // если действие для удаления
        if( $request->get('action') == 'delete' ) {
            // удалим из бд
            CharlistOptionVariants::destroy( $ids );
        }
        return redirect()->back()->with('message', 'Параметр удален');
    }


    public function postAdd( Request $request ){
//         print_r($request->all());exit();
        CharlistOptionVariants::create( $request->all() );

        return redirect()->back()->with('message', 'Параметр добавлен');
    }


    public function getEdit( $id )
    {
        $post = CharlistOptionVariants::find($id);
        $title = 'Редактирование параметра';

        return view('admin.editCharlistOptionVariant', compact(['post', 'title']));
    }


    public function postEdit( Request $request, $id )
    {
        $feature = CharlistOptionVariants::findOrFail($id);

        // обновляет значения в таблице с опциями товаров
        CharlistOptions::whereValue( $feature->name )->whereCharlistId( $feature->charlist_id )->update(['value' => $request->name ]);

        $feature->update( $request->all() );

        return redirect()->back()->with('message', 'Параметр успешно обновлен!');
    }


}