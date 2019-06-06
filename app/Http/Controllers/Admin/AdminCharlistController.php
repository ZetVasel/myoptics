<?php
/**
 * Created by PhpStorm.
 * User: Dvacom
 * Date: 29.05.2017
 * Time: 10:12
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Charlist;
use App\Models\CharlistOptions;
use App\Models\CharlistOptionVariants;

use App\Http\Requests\FeatureRequest;


class AdminCharlistController extends AdminBaseController
{
    public function getIndex(){

        $title = "Характеристики заказа";
        $charlist = Charlist::paginate(15);

        return view('admin.showCharlist', compact(['title', 'charlist']));
    }

    public function postIndex( Request $request )
    {
        $ids = $request->get('check');
        // если действие для удаления
        if( $request->get('action') == 'delete' ) {
            // удалим из бд
            CharlistOptionVariants::whereCharlistId( $ids )->delete();
            CharlistOptions::whereCharlistId( $ids )->delete();
            Charlist::destroy( $ids );
        }
        return redirect()->back()->with('message', 'Характеристика удалена');
    }


    public function postAdd( FeatureRequest $request ){

        Charlist::create( $request->all() );
        return redirect()->back()->with('message', 'Характеристика добавлена');
    }


    public function getEdit( $id )
    {
        $post = Charlist::find($id);
        $title = 'Редактирование параметра';

        return view('admin.editCharlist', compact(['post', 'title']));
    }


    public function postEdit( FeatureRequest $request, $id )
    {
        $feature = Charlist::findOrFail($id)->update( $request->all() );

        return redirect('/master/charlist/edit/' . $id)->with('message', 'Характеристика успешно обновлена!');
    }
}