<?php
/**
 * Created by PhpStorm.
 * User: Dvacom
 * Date: 21.02.2017
 * Time: 14:42
 */

namespace App\Http\Controllers\Frontend;
use Illuminate\Http\Request;
use App\Http\Controllers\Frontend\BaseController;
use Illuminate\Support\Facades\App;
use Session;
use App\Models\GoogleAuth;
use App\Models\FacebookAuth;

class SocialsAuthController extends BaseController
{


    //авторизация через google
    public function authGoogle(Request $request){
        $google = new GoogleAuth;//новый экземпляр класса

        if($code = $request->get('code')){
            $google->setAuthSession($code);
            return redirect('/');
        }else{
            echo 'error';
        }

    }

    //авторизация через Facebook
    public function authFacebook(Request $request){
        $facebook = new FacebookAuth();//новый экземпляр класса

        if($code = $request->get('code')){
            $facebook->setAuthSession($code);
            return redirect('/');
        }else{
            echo 'error';
        }

    }

}
