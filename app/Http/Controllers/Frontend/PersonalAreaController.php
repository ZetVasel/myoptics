<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Frontend\BaseController;
use App\Models\Page;
use App\Models\Orders;
use App\Models\OrdersProducts;
use App\User;
use App\Models\UserAddresses;
use App\Models\Notification;
use App\Http\Requests\LogInRequest;
use App\Http\Requests\FrontendCreateUserRequest;
use App\Http\Requests\FrontendUpdateUserRequest;
use Auth;
use Hash;

class PersonalAreaController extends BaseController
{
    public function index(){

        $page = Page::whereSlug('personal-area')->first();

        if (Auth::check()){
            $user = Auth::user();

            $orders = Orders::with(['orderedproducts' => function($query){

                $query->leftJoin('dv_products', 'dv_orders_products.product_id', '=', 'dv_products.id')
                        ->select(['dv_orders_products.*', 'dv_products.imgs', 'dv_products.slug','dv_products.name','dv_products.main_img'])
                        ->get();
            }])
            ->whereUserId( $user->id )->get();

            $orders_ids = [];
            $total_ordered = 0;
            foreach( $orders as $ord ){
                $total_ordered += $ord->total_cost;
                array_push($orders_ids, $ord->id);
            }
            // $orders_kits = OrdersProducts::getOrdersKits( $orders_ids );
        }
        return view('frontend.personalArea', compact(['page', 'user', 'orders', 'total_ordered', 'orders_kits']));
    }


    public function getRegister(){
        if (Auth::check()){
            return redirect('/');
        }
        $page = new Page;
        $page->meta_title = 'Регистрация';

        return view('frontend.showRegister', compact(['page']));
    }


    public function notifications(){
        if (!Auth::check()){
            return redirect('/');
        }else{
            $user = Auth::user();
        }
        $page = new Page;
        $page->meta_title = $page->name = 'Напоминание о замене линз';
        $page->slug = 'notifications';

        $notification = Notification::where('user_id', $user->id)->get();


        return view('frontend.showNotifications', compact(['page', 'notification']));
    }

    public function postNotifications(Request $request){

        if($request->ajax()){
            $user = Auth::user();
            $id = (int)$request->get('id');
            $notification = Notification::whereId($id)->first();
            if($notification['user_id'] == $user['id']){
                Notification::destroy($id);
            }
            return '1';
        }

        $repeat = $request->get('repeat') == 'on' ? 1 : 0;
        $user = Auth::user();
        Notification::create(['delivery_date' => $request->get('delivery_date'), 'interval' => $request->get('interval'), 'repeat' => $repeat, 'user_id' => $user->id, 'last_send' => $request->get('delivery_date')]);
        return redirect()->back()->with('message', 'Успешно');
    }



    public function postLogin( LogInRequest $request ){


//        print_r($request->all());exit();

        if( Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')], $request->get('rememberMe') == 'on') )
            return redirect()->back();
        else
            return redirect()->back()->with('error', '');
    }


    public function logout(){

        Auth::logout();
        return redirect()->back();
    }


    public function postRegister( Request $request ){


//        print_r($request->all());exit();

        $user = User::create([
            'email'         => $request->get('email'),
            'phone'         => $request->get('phone'),
            'firstName'     => $request->get('firstName'),
            // 'address'       => $request->get('address'),
            'password'      => Hash::make($request->get('password')),
            'activated'     => 1,
            'permissions'   => 'user'
        ]);
        Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')]);
        return redirect()->back();
    }


    public function getEditInfo(){

        if( $user = Auth::user() ){
            if(UserAddresses::where('user_id', $user->id)->first()) {

                $user = User::join('dv_user_address', 'users.id', '=', 'dv_user_address.user_id')
                    ->where('users.id', $user->id)
                    ->first();
            }

            $page = Page::whereSlug('edit-info')->first();
            return view('frontend.editUserInfo', compact(['page', 'user']) );
        }
        else
            return redirect()->back();
    }


    public function postEditInfo( Request $request ){

        if( $user = User::find( Auth::user()->id ) ){

            $user->login = $request->get('login');
            $user->email = $request->get('email');
            // $user->address = $request->get('address');
            $user->phone = $request->get('phone');

            $user->firstName  = $request->get('firstName');
            $user->lastName   = $request->get('lastName');
            $user->middleName = $request->get('middleName');

            $userAddresses          = UserAddresses::firstOrNew(array( 'user_id' => $user->id));
            $userAddresses->city    =  $request->get('city');
            $userAddresses->address =  $request->get('address');


            if( $request->get('password') != "" )
                $user->password = Hash::make( $request->get('password') );

            $user->save();
            $userAddresses->save();
        }
        return redirect()->back();
    }
}
