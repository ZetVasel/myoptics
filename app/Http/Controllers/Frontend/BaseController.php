<?php

namespace App\Http\Controllers\Frontend;

use App\Models\ProductReviews;
use App\Models\UserAddresses;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Models\Page;
use App\Models\Settings;
use App\Models\Category;
use App\Models\Products;
use App\Models\Feedback;
use App\Models\FeedbackContact;
use App\Models\News;
// use App\Models\Articles;
use App\Models\GoogleAuth;
use App\Models\FacebookAuth;
use App\Models\Subscribe;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\FeedbackRequest;

use Mailgun\Mailgun;
use Session;
// use Mail;
 use Auth;
// use DB;

class BaseController extends Controller
{

    public function __construct(){
        $pages = Page::all();
        $categories = Category::all()->toHierarchy();

        $userDiscount = 0;

        $settings = Settings::first();
        if (Auth::check()){
            $user = Auth::user();

            if(UserAddresses::where('user_id', $user->id)->first()){
                $user = User::join('dv_user_address', 'users.id', '=', 'dv_user_address.user_id')
                    ->where('users.id', $user->id)
                    ->first();
            }


//            print_r($user);exit();

            if(User::getOrderedSum($user->id) >= $settings->sum){
                //коефициент скидки
                $userDiscount = $settings->discount / 100;
            }

        }
        $compare_ids = Session::has('compare') ? Session::get('compare') : [];

        if( $cur_ids = Session::get('in_cart') )
            $cart_info = Products::priceAndQuantity( $cur_ids );
        else
            $cart_info = ['quantity' => 0, 'total_price' => 0];


        $footCatArray = [];

        $categoriesFoot = Category::where('parent_id', null)->take(3)->get()->toArray();

        $pageFoot = Page::whereType('main')->skip(1)->take(2)->get()->toArray();

        $footCatArray = array_merge($categoriesFoot, $pageFoot);

        $googleAuthLink = GoogleAuth::generateLink();//ссылка для авторизации через Google
        $facebookAuthLink = FacebookAuth::generateLink();//ссылка для авторизации через Facebookz



    	view()->share(compact(['compare_ids', 'pages', 'settings', 'categories', 'favorites', 'cart_info','links', 'footCatArray', 'googleAuthLink', 'facebookAuthLink', 'user', 'userDiscount']));
    }



    public function sendRewiew(Request $request){
        $starsCount = (int)$request->get('stars');
        $product_id = (int)$request->get('product_id');
        $text = $request->get('text');
        if (Auth::check()){
            $user = Auth::user();
            $name = $user->firstName;
            $mail = $user->email;
        }else{
            $name = $request->get('name');
            $mail = $request->get('mail');
        }


        ProductReviews::create([
            'product_id' => $product_id,
            'rating' => $starsCount,
            'name' => $name,
            'email' => $mail,
            'text' => $text,
            'visible' => 1
        ]);

        $product = Products::whereId($product_id)->first();

        Products::whereId($product_id)->update(['votes' => ($product->votes + 1), 'rating_sum' => ($product->rating_sum + $starsCount)]);

        return redirect()->back()->with( 'message','Отзыв успешно добавлен!' );


    }


    public function setShow(Request $request){
        Session::put('show', $request->get('show'));
    }


    public function search( Request $request){

        $search = $request->get('search');

        $page = new Page;
        $page->name = $page->meta_title = 'Поиск';
        $products = Products::where('name', 'like', '%' . $search . '%')->paginate(12);
        $products =  Products::refImg($products);

        return view('frontend.showSearchResults', compact(['products', 'search', 'page']));
    }


    public function feedback( FeedbackRequest $request ){

        Feedback::create( $request->except('_token') );   

         return redirect()->back()->with('massege','Вам перезвонят в ближайшее время. Спасибо за Вашу заявку!') ;
    } 


    public function SubscribePost( Request $request ){

        Subscribe::create( $request->all() );

        // \Mail::send('emails.SendSubsc', function($message) 
        // {
        //     $message->from('svitloshopmail@gmail.com', 'Новый заказ');
        //     $message->to('svitloshopmail@gmail.com', 'Admin')->subject('Новый заказ' );
        // });

        return redirect()->back()->with( 'message','' );
    }


}
