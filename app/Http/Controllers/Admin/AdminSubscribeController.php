<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Subscribe;


class AdminSubscribeController extends AdminBaseController
{

    public function getIndex(){

        $title ="Рассылка новостей";
        $subscribers = Subscribe::paginate(15);
        $subscribers_count = Subscribe::count();

        return view('admin.showSubscribers', compact(['title','subscribers','subscribers_count']));
    }

    public function postIndex( Request $request ) {

        $ids = $request->get('check');
        // если действие для удаления
        if( $request->get('action') == 'delete' )
            Subscribe::destroy( $ids );


        return redirect()->back();
    }
     public function postSendEmail( Request $request ){


        // отправка почты
        $theme = $request->get('theme');
        $msg_text = $request->get('text');
        $test = $request->root();
            // print_r($test);exit();
        //ищем /uploads/tiny/' и добавляем адрес
        $msg_text= str_replace('/uploads/tiny/', $test .'/uploads/tiny/', $msg_text);

        $ids = $request->get('subscriber');
        //если есть ids  подпищиков, отправляем им
        if(isset($ids)){
        $subscribers = Subscribe::select('mail')->whereIn('id', $ids)->get();

        }
       //если нет, отправляем все подпищикам
        else{
            $subscribers = Subscribe::select('mail')->get();
        }    // print_r($subscribers); exit();


        foreach( $subscribers as $subscr ){
            $u_email = $subscr->mail;
            \Mail::send('emails.news', ['theme' => $theme, 'msg_text' => $msg_text ],
            function($message) use ($theme, $u_email){
                $message->to($u_email, '')->subject($theme);
            });
         }
       

        return redirect()->back()->with('message', 'Сообщение успешно отправлено подписчикам');
    }
}


