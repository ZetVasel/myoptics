<?php

namespace App\Http\Controllers\Frontend;

use App\Models\OrderProductCharlist;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Frontend\BaseController;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\RemoveFromCartRequest;
use App\Http\Requests\FullOrderRequest;
use App\Http\Requests\ChangeQuantityRequest;

use App\Models\Page;
use App\Models\Settings;
use App\Models\Products;
use App\Models\Delivery;
use App\Models\Warehouse;
use App\Models\Payment;
use App\Models\Orders;
use App\Models\OrdersProducts;
use App\Models\UserAddresses;

use App\Models\CharlistOptions;
use App\Models\Charlist;
use App\Models\CharlistOptionVariants;

use Mailgun\Mailgun;
use Session;
use Response;
use Auth;
use Lang;
class CartController extends BaseController
{

    public function prodToCart(Request $request){
        // print_r($request->all());exit;
        $cur_ids = [];
        //проверка на эдиничность характеристик заказа
        if($request->has('single_quanbtity')){
              $cur_ids = Products::addToCart( Session::get('in_cart'), $request->get('prod_id'), $request->get('single_quanbtity') , $request->get('feature'));
        }else{
            $cur_ids = Products::addToCart( Session::get('in_cart'), $request->get('prod_id'), null ,null, $request->get('quantityLeft'), $request->get('quantityRight'),$request->get('featureleft'), $request->get('featureright'));
        }

        Session::put('in_cart', $cur_ids);

        return redirect(Session::get('_previous')['url'])->with('message' , 'Товар(-ы) успешно добавлен(-ы) в корзину!');

    }



    public function getCartStepTwo(){
        $page = Page::where('slug', '=', 'cartStepTwo')->first();
        $delivery = Delivery::all();
        $payment = Payment::all();
        $warehouse = Warehouse::all(); //np

        return view('frontend.showCartStepTwo', compact(['page', 'delivery', 'payment', 'warehouse']));

    }

    public function confirmOrder(Request $request){
        $user_id = 0;
        $userDiscount = 0;
        if( $user = Auth::user() ){
            $user_id = $user->id;
            $settings = Settings::first();
            if(User::getOrderedSum($user->id) >= $settings->sum){
                //коефициент скидки
                $userDiscount = $settings->discount / 100;
            }
        }


        $order = Orders::create([
            'comment' => $request->get('coment'),
            'user_id' => $user_id,
            'fio' => $request->get('name'),
            'city' => $request->get('city'),
            'phone' => $request->get('phone'),
            'address' => $request->get('address'),
            'email' => $request->get('email'),
            'delivery' => $request->get('delivery'),
            'warehouse' => $request->get('warehouse'), //np
            'payment' => $request->get('buy'),
            'status' => 0,
            'paid' => 0,
            'total_cost' => $request->get('total_cost')
        ]);

        $cartProduct = [];


        if( $cur_ids = Session::get('in_cart') ){

            foreach ($cur_ids as $arrKey => $item){
                foreach($item as $key => $value){

                    $charList = [];
                    foreach ($value['charlist'] as $char){
                        if(empty($char))
                            continue;
                        $valueInfo = CharlistOptionVariants::whereId($char)->first();
                        $charInfo = Charlist::whereId($valueInfo->charlist_id)->first();
                        $charList[] = [
                            'name' => $charInfo->name,
                            'value' => $valueInfo->name
                        ];
                    }

                    $products = Products::whereId($key)->get();
                    $cartProduct[] = [
                        'product'  => Products::refImg($products),
                        'quantity' => $value['quantity'],
                        'charlist' => $charList,
                        'arrKey'   => $arrKey
                    ];
                }
            }

        }

        foreach ($cartProduct as $item){
            $orderProducts = OrdersProducts::create([
               'order_id' => $order->id,
               'product_id' => $item['product'][0]->id,
               'quantity' => $item['quantity'],
               'discount' => $userDiscount,
               'cost' => $item['product'][0]->price
            ]);

            foreach($item['charlist'] as $char){

                OrderProductCharlist::create([
                    'order_product_id' => $orderProducts->id,
                    'name' => $char['name'],
                    'value' => $char['value']

                ]);

            }
        }

        $cur_ids = [];

        Session::put('in_cart', '');

        return redirect('/')->with('message' , 'Ваш заказ успешно оформлен. Мы с Вами свяжемся в ближайшее время. Спасибо!');
    }


    public function postCartStepTwo(Request $request){
        $page = Page::where('slug', '=', 'cartStepThree')->first();
        $name = $request->get('name');
        $mail = $request->get('mail');
        $phone = $request->get('phone');
        $city = $request->get('city');
        $coment = $request->get('coment');
        $addres = $request->get('addres');

        $delivery = $request->get('delivery');
        $warehouse = $request->get('warehouse'); //np
        $buy = $request->get('buy');


        $deliveryInfo = Delivery::whereId($delivery)->first();
        $warehouseInfo = Warehouse::whereId($warehouse)->first(); //np
        $buy = Payment::whereId($buy)->first();


        $countOrder = Orders::orderBy('id', 'DESC')->first();
        if($countOrder){
            $countOrder = $countOrder->id + 1;
        }else{
            $countOrder = 1;
        }

        $cartProduct = [];

        if( $cur_ids = Session::get('in_cart') ){

            foreach ($cur_ids as $arrKey => $item){
                foreach($item as $key => $value){

                    $charList = [];
                    foreach ($value['charlist'] as $char){
                        if(empty($char))
                            continue;
                        $valueInfo = CharlistOptionVariants::whereId($char)->first();
                        $charInfo = Charlist::whereId($valueInfo->charlist_id)->first();
                        $charList[] = [
                            'name' => $charInfo->name,
                            'value' => $valueInfo->name
                        ];
                    }

                    $products = Products::whereId($key)->get();
                    $cartProduct[] = [
                        'product'  => Products::refImg($products),
                        'quantity' => $value['quantity'],
                        'charlist' => $charList,
                        'arrKey'   => $arrKey
                    ];
                }
            }

        }

        $cart_info = Products::priceAndQuantity( $cur_ids );

        return view('frontend.showCartStepThree', compact(['page', 'delivery', 'buy', 'deliveryInfo', 'addres', 'coment', 'city', 'phone', 'mail', 'name', 'cartProduct', 'countOrder', 'cart_info', 'warehouse', 'warehouseInfo'])); //np
    }



	// отображение корзины
	public function getCart(){

		$page = Page::where('slug', '=', 'cart')->first();

		$cartProduct = [];

		if( $cur_ids = Session::get('in_cart') ){

		    foreach ($cur_ids as $arrKey => $item){
		        foreach($item as $key => $value){

		            $charList = [];

		            foreach ($value['charlist'] as $char){
                        if(empty($char))
                            continue;
		                $valueInfo = CharlistOptionVariants::whereId($char)->first();
		                $charInfo = Charlist::whereId($valueInfo->charlist_id)->first();
		                $charList[] = [
		                    'name' => $charInfo->name,
                            'value' => $valueInfo->name
                        ];
                    }

                    $products = Products::whereId($key)->get();
		            $cartProduct[] = [
		                'product'  => Products::refImg($products),
                        'quantity' => $value['quantity'],
                        'charlist' => $charList,
                        'arrKey'   => $arrKey
                    ];
                }
            }

		}
		$delivery_methods = Delivery::all();
		$warehouse_methods = Warehouse::all(); //np
		$payment_method = Payment::all();

		return view('frontend.showCart', compact(['cartProduct','page','cur_ids','delivery_methods','payment_method','warehouse_methods'])); //np
	}


	// Добавление товаров в корзину
    public function toCart( AddToCartRequest $request ){

    	if($request->has('quantity')){
    		$cur_ids = Products::addToCart( Session::get('in_cart'), $request->get('product_id'), $request->get('quantity') );
    	}
    	else{
    		$cur_ids = Products::addToCart( Session::get('in_cart'), $request->get('product_id'), 1 );
    	}

		Session::put('in_cart', $cur_ids);
		$cart_info = Products::priceAndQuantity( $cur_ids );
		$data = ['quantity' => $cart_info['quantity'], 'title' => Lang::choice('Товар|Товара|Товаров', $cart_info['quantity'], array(), 'ru') ];

		return Response::json($data);
    }


	// изменение количества товара в корзине
	public function changeQuantity( Request $request ){

		if( $request->all() ) {
			$product_id = (int)$request->get('product');
			$arrKey = (int)$request->get('arrkey');
			$quantity = (int)$request->get('quantity');

			$cur_ids = Session::get('in_cart');

			$cur_ids[$arrKey][$product_id]['quantity'] = $quantity;

			Session::put('in_cart', $cur_ids);
		}
		return redirect('/cart');
	}


	public function newFeature(Request $request){
	    $product_id = (int)$request->get('product_id');
	    $arrkey = (int)$request->get('arrkey');
	    $feature = $request->get('feature');
        $cur_ids = Session::get('in_cart');
        $cur_ids[$arrkey][$product_id]['charlist'] = $feature;
        Session::put('in_cart', $cur_ids);
        return redirect('/cart');
    }


	public function getCharList(Request $request){
	    $charOptions = CharlistOptions::whereProductId((int)$request->get('product_id'))->get();

	    $charlist = [];

	    foreach ($charOptions as $item){

	        $id = CharlistOptionVariants::whereName($item->value)->first();

            if(!$id){
               continue;
            }

	        $charlist[$item->charlist_id][] = [
	            'id' => $id->id,
                'value' => $item->value
            ];
        }

        $charListResponse = [];


        foreach ($charlist as $key => $value){
            $char = Charlist::whereId($key)->first();

            $charListResponse[] = [
                'name' => $char->name,
                'value' => $value
            ];
        }

        $response = '';

        foreach ($charListResponse as $value){


            $response .= '<div class="item">
				<div class="name">'.$value['name'].'</div>
				<div class="value">
					<select data-smart-positioning="false" class="featureSelect" name="feature[]">';

            foreach ($value['value'] as $charValue) {


                $response .= '<option value="'.$charValue['id'].'">'.$charValue['value'].'</option>';
            }


            $response .= '
					</select>
				</div>
			</div>';
        }


        return response()->json($response);

    }


	public function quickOrder( Request $request ){
		$user_id = Auth::check() ? Auth::id() : 0;
		$size = $request->get('size');
		$product_id = $request->get('product_id');
		$color_id = $request->get('color_id');

		$product = Products::whereId($product_id)->first();
		if( $product ){
			$cost = $product->price - $product->price * $product->p_discount/100;
			$new_order = Orders::create(['name' => $request->get('name'), 'user_id' => $user_id, 'phone' => $request->get('phone'), 'total_cost' => $cost]);
			OrdersProducts::create(['order_id' => $new_order->id, 'product_id' => $product_id,  'quantity' => 1, 'cost' => $cost]);

			return 1;
		}
		return 0;
	}

	// удаление с корзины
	public function removeFromCart( Request $request ){
        $arrKey = (int)$request->get('arrkey');
		$cur_ids = Session::get('in_cart');
		unset( $cur_ids[ $arrKey ] );
		Session::put('in_cart', $cur_ids );

		return redirect('/cart');
	}

	public function fullOrder( FullOrderRequest $request ){

		$user_id = Auth::check() ? Auth::id() : 0;

		$insert = [
				'name' 	  	=> $request->get('name'),
				'town' 	  	=> $request->get('town'),
				'address' 	=> $request->get('address'),
				'phone'   	=> $request->get('phone'),
				'email'   	=> $request->get('email'),
				'comment'   => $request->get('comment')
		];


		$cur_ids = Session::get('in_cart');

		$new_order = Orders::create( array_merge($insert, ['user_id' => $user_id], ['status' => 0], ['paid' => 0], ['delivery' => $request->get('delivery_method')], ['warehouse' => $request->get('warehouse_method')], ['payment' => $request->get('payment_method')] ) ); //np
		
		$total_price = OrdersProducts::saveOrderProducts($cur_ids, $new_order);

		Orders::whereId( $new_order->id )->update(['total_cost' => $total_price ]);

		OrdersProducts::sendOrderToClient($new_order->id);

		Session::forget('in_cart');

		$data = ['quantity' => 0, 'total_price' => 0 ];
		Session::put('cart_info', $data);
		return redirect()->back()->with('message', 'Ваш заказ успешно оформлен. Мы с Вами свяжемся в ближайшее время. Спасибо!');
	}

    public function getContent(){
        $string = file_get_contents('https://novaposhta.ua/shop/office/getjsonwarehouselist');
        $a = json_decode($string, true);
        $warehouse_by_city = array();
            foreach($a['response'] as $warehouse){
                $warehouse_by_city[$warehouse['cityRu']][] = array(
                    'cityRu' => $warehouse['cityRu'],  //название города
                    'addressRu' => $warehouse['addressRu'] //адрес отделения
            );
        };
        ksort($warehouse_by_city); //сортируем по городам
        echo '<select name="warehouse">';
            foreach($warehouse_by_city as $city){
                foreach($city as $warehouse){
                    echo '<option value="">'. $warehouse['cityRu'] . ' - ' . $warehouse['addressRu'] .'</option>';
                }

            };
        echo '</select>';


    }

}
