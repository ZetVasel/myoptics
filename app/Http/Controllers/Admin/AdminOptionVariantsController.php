<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\AdminBaseController;

use App\Models\Features;
use App\Models\OptionVariants;
use App\Models\Options;

use App\Http\Requests\CreateOptionVariantsRequest;
use App\Http\Requests\UpdateOptionVariantsRequest;

class AdminOptionVariantsController extends AdminBaseController
{
    public function getShow( $id ){

        $feature = Features::find($id);
        $title = 'Значения параметра "'. $feature->name. ' "';
        $options = OptionVariants::whereFeatureId( $id )->paginate(15);

        return view('admin.showOptionVariants', compact(['title', 'options', 'id', 'feature']));
    }


    public function postShow( $id, Request $request ) 
    {
        $ids = $request->get('check');
        // если действие для удаления
        if( $request->get('action') == 'delete' ) {
            // удалим из бд
            OptionVariants::destroy( $ids );
        }
        return redirect()->back()->with('message', 'Параметр удален');
    }


    public function postAdd( CreateOptionVariantsRequest $request ){
        // print_r($request->all());exit();
        OptionVariants::create( $request->all() );

        return redirect()->back()->with('message', 'Параметр добавлен');
    }


    public function getEdit( $id )
    {
        $post = OptionVariants::find($id);
        $title = 'Редактирование параметра';
        
        return view('admin.editOptionVariant', compact(['post', 'title']));
    }


    public function postEdit( UpdateOptionVariantsRequest $request, $id )
    {
        $feature = OptionVariants::findOrFail($id);

        // обновляет значения в таблице с опциями товаров
        Options::whereValue( $feature->name )->whereFeatureId( $feature->feature_id )->update(['value' => $request->name ]);

        $feature->update( $request->all() );

        return redirect()->back()->with('message', 'Параметр успешно обновлен!');
    }
}
