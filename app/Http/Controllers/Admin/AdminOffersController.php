<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminBaseController;

use App\Http\Requests;
use App\Http\Requests\AddOffersRequest;
use App\Http\Requests\EditOffersRequest;


use App\Models\Products;
use App\Models\Offers;
use App\Models\OfferProducts;

class AdminOffersController extends AdminBaseController
{
    public function getIndex(){
        $title = 'Акции';
        $offers = Offers::orderBy('id', 'DESCR')->with('offerproducts')->paginate(10);

        return view('admin.showOffers', compact(['title', 'offers']));
    }

    /**
     * POST index
     */
    public function postIndex( Request $request ) {
        $ids = $request->get('check');
        // удаляем
        if( $request->get('action') == 'delete' )
            Offers::destroy( $ids );
        return redirect()->back();
    }


    /**
     * Добавление акций
     * 
     * @return collection $post       = пустая коллекция модели Offers
     * @return string     $title      = заголовок страницы
     */
    public function getAdd(){
        $title = "Добавление акций";

        $post = new Offers;
        $post->image = "";

        return view('admin.editOffers', compact([ 'title', 'post' ]));        
    }

    /**
     * POST Добавление акций
     * 
     */
    public function postAdd( AddOffersRequest $request ){

        $offer = Offers::create( $request->except( ['_token', 'image']) );
        if( $image = $request->file('image') )
            Offers::saveImage( $image, $offer->id );

        return redirect('/master/offers')->with('success', 'Запись добавлена');
    }

    /**
     * Редактирование акций
     * 
     * @param int $id = ID записи
     * 
     * @return collection $post       = акция
     * @return string     $title      = заголовок страницы
     */
    public function getEdit( $id ){
        $title = "Редактирование акций";
        $post = Offers::find( $id );
       
        return view('admin.editOffers', compact(['title', 'post']));
    }

    /**
     * POST Редактирование акций
     * 
     * @param int $id           = ID записи
     * @param Request $request  = данные из вида
     */    
    public function postEdit( EditOffersRequest $request, $id ){
        $input = $request->except( ['_token', 'image'] );
        Offers::whereId($id)->update( $input );

        if( $image = $request->file('image') )
            Offers::updateImage( $image, $id );

        return redirect()->back()->with('success', 'Запись обновлена');
    }

    /**
     * Редактирование товаров акции
     * 
     * @param int $id           = ID записи
     * 
     * @return collection $post       = акция
     * @return collection $products   = все продукты
     * @return string     $title      = заголовок страницы
     */    
    public function getProducts( $id ){
        $title = "Редактирование товаров акции";
        
        $post = Offers::with('offerproducts')->find( $id );
        $post->related = $post->offerproducts->lists('product_id')->all();

        $products = Products::all();
       
        return view('admin.editOfferProducts', compact(['title', 'post', 'products']));
    }    

    /**
     * POST Редактирование товаров акции
     * 
     * @param int $id           = ID записи
     * @param Request $request  = данные из вида ( массив с id товаров )
     */    
    public function postProducts( Request $request, $id ){
        OfferProducts::where('offer_id', $id)->delete();
        // если массив не пустой
        if( count($request->related) ){
            $insert = [];
            foreach ($request->related as $related)
                $insert[] = [
                    'offer_id'    => $id,
                    'product_id'  => $related
                ];

            OfferProducts::insert($insert);
        }
        return redirect('/master/offers')->with('ok', 'Сохранено');
    }      
}
