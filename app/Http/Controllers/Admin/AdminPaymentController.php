<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\PaymentRequest;
use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Payment;

class AdminPaymentController extends AdminBaseController
{

	public function checkoutSettingsManagement()
	{
		$title = 'Управление настройками оформления заказа';
		return view('admin.showCheckoutSettings', compact('title'));
	}
	
    public function getIndex(){

    	$title = 'Способы оплаты';
    	$payment = Payment::latest()->paginate(15);
    	$payment_count = Payment::count();

    	return view('admin.showPayments', compact(['title', 'payment', 'payment_count']));
    }


	public function postIndex( Request $request ) {

		$ids = $request->get('check');
		// если действие для удаления
		if( $request->get('action') == 'delete' ) {
			// удалим из бд
			Payment::destroy( $ids );
		}
		return redirect()->back();
	}


	public function getAdd()
	{
		$post = new Payment;
		$title = 'Создание способа оплаты';
		return view('admin.editPaymentPost', compact(['post','title']));
	}


	public function postAdd( PaymentRequest $request )
	{
		$post = Payment::create($request->all());

		return redirect('master/payment')->with('success', 'Способ оплаты успешно добавлен!');
	}


	public function getEdit( $id )
	{
		$post = Payment::find($id);
		$title = 'Редактирование способа оплаты';
		return view('admin.editPaymentPost', compact(['post', 'title']));
	}


	public function postEdit( PaymentRequest $request, $id )
	{
		$payments = Payment::findOrFail($id);
		$payments->update($request->all());
		
		return redirect('/master/payment/edit/' . $id)->with('success', 'Способ оплаты успешно обновлен!');
	}
}
