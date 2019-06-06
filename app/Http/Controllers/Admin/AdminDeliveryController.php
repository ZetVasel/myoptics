<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\DeliveryRequest;
use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Delivery;

class AdminDeliveryController extends AdminBaseController
{

	public function checkoutSettingsManagement()
	{
		$title = 'Управление настройками оформления заказа';
		return view('admin.showCheckoutSettings', compact('title'));
	}

    public function getIndex(){

    	$title = 'Способы доставки';
    	$delivery = Delivery::latest()->paginate(15);
    	$delivery_count = Delivery::count();

    	return view('admin.showDelivery', compact(['title', 'delivery', 'delivery_count']));
    }


	public function postIndex( Request $request ) {

		$ids = $request->get('check');
		// если действие для удаления
		if( $request->get('action') == 'delete' ) {
			// удалим из бд
			Delivery::destroy( $ids );
		}
		return redirect()->back();
	}


	public function getAdd()
	{
		$post = new Delivery;
		$title = 'Создание способа доставки';
		return view('admin.editDeliveryPost', compact(['post','title']));
	}


	public function postAdd( DeliveryRequest $request )
	{
		$post = Delivery::create($request->all());

		return redirect('master/delivery')->with('success', 'Способ доставки успешно добавлен!');
	}


	public function getEdit( $id )
	{
		$post = Delivery::find($id);
		$title = 'Редактирование способа доставки';
		return view('admin.editDeliveryPost', compact(['post', 'title']));
	}


	public function postEdit( DeliveryRequest $request, $id )
	{
		$delivery = Delivery::findOrFail($id);
		$delivery->update($request->all());
		
		return redirect('/master/delivery/edit/' . $id)->with('success', 'Способ доставки успешно обновлен!');
	}
}
