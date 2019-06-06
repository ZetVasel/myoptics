<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Links;
use File;
class AdminLinksController extends AdminBaseController
{
    public function getIndex(){

        $title = 'Links';
        $remotes_count = 0;

        $all_remotes = Links::all();
        $type = ['Социальные сети'];
        $remotes_count = count($all_remotes);

        return view('admin.ShowLinks', compact(['all_remotes', 'remotes_count', 'title', 'type']));
    }

    public function getEdit($id){
        
        $title = 'Links';
        $type = ['Социальные сети'];
        $remote  = Links::where('id', '=', $id)->first();

        return view('admin.EditLinks', compact(['remote', 'title', 'type']));
    }

    public function getAdd(){
        $title = 'Links';
        $type = ['Социальные сети'];
        $remote = new Links;
        $remote->img = '';
        return view('admin.EditLinks', compact(['title', 'remote', 'type']));
    }

    public function actionAdd(Request $request){

        $ico = $request->file('ico');
        $icoid = null;
        if ($ico)
        {
            $icoid = Links::saveIco($ico);
        }
        
        Links::insert( array_merge( $request->except( ['ico', '_token'] ), ['img' => $icoid] ) );
        return redirect()->back();
    }

    public function postEdit(Request $request, $id){

        $reqest_all = $request->all() ;
        if( $ico = $request->file('ico') )
        {
            if (!is_null(Links::find($id)->img))
                File::delete( public_path('uploads/ico/'.Links::find($id)->img ));
           
            $icoid = Links::saveIco($ico);
            Links::where('id', '=', $id)->update(['img' => $icoid]);
        }

        
        Links::where('id', '=', $id)->update(array_merge( $request->except( ['ico', '_token'] ) ) );
        return redirect()->back();
    }


    public function actionDelete(Request $request) {

        $id = $request->get('check')[0];
        if (!is_null(Links::find($id)->img))
                File::delete( public_path('uploads/ico/'.Links::find($id)->img ));
        // удалим из бд
        Links::destroy( $id );

        return redirect()->back();
    }
}