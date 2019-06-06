<?php
/**
 * Created by PhpStorm.
 * User: Dvacom
 * Date: 06.06.2017
 * Time: 13:54
 */

namespace App\Http\Controllers\Frontend;


use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Models\Page;
use Auth;

class ServiceController extends BaseController
{
    public function getIndex(){

        if (!Auth::check()){
            return redirect('/');
        }else{
            $user = Auth::user();
        }

        $page = new Page;
        $page->name = 'Сервисная заявка';
        $page->meta_title = 'Сервисная заявка';
        $page->slug = 'service';
        $service = Feedback::where('user_id', $user->id)->get();
        return view('frontend.showService', compact(['page', 'service']));
    }

    public function postIndex(Request $request){

        if (!Auth::check()){
            return redirect('/');
        }else{
            $user = Auth::user();
        }

        $post = Feedback::create(['title' => $request->get('title'), 'comment' => $request->get('text'), 'user_id' => $user->id]);

        // если загрузка файлов
		if( $request->file('file')[0] )
            Feedback::saveFile( $request, $post->id );


        return redirect()->back()->with('message', 'Успешно');
    }
}