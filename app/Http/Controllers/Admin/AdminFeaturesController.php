<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\AdminBaseController;

use App\Models\Features;
use App\Models\OptionVariants;
use App\Models\Options;

use App\Http\Requests\FeatureRequest;

class AdminFeaturesController extends AdminBaseController
{
    public function getIndex(){

        $title = "Параметры фильтрации";
        $features = Features::paginate(15);

        return view('admin.showFeatures', compact(['title', 'features']));
    }


    public function postIndex( Request $request ) 
    {
        $ids = $request->get('check');
        // если действие для удаления
        if( $request->get('action') == 'delete' ) {
            // удалим из бд
            OptionVariants::whereFeatureId( $ids )->delete();
            Options::whereFeatureId( $ids )->delete();
            Features::destroy( $ids );
        }
        return redirect()->back()->with('message', 'Параметр удален');
    }


    public function postAdd( FeatureRequest $request ){

        Features::create( $request->all() );
        return redirect()->back()->with('message', 'Параметр добавлен');
    }


    public function getEdit( $id )
    {
        $post = Features::find($id);
        $title = 'Редактирование параметра';
        
        return view('admin.editFeature', compact(['post', 'title']));
    }


    public function postEdit( FeatureRequest $request, $id )
    {
        $feature = Features::findOrFail($id)->update( $request->all() );

        return redirect('/master/features/edit/' . $id)->with('message', 'Параметр успешно обновлен!');
    }
}
