<?php

namespace App\Http\Controllers\Admin;

use App\Models\DvFeedbacksFiles;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Feedback;
use App\Models\ProductReviews;


class AdminFeedbackController extends AdminBaseController
{
    public function feedbackTypes()
    {
        $title = 'Обратная связь';
        $product_rev = ProductReviews::count();
        $feedbacks = Feedback::whereCompleted(0)->count();

        return view('admin.showFeedbackTypes', compact(['title', 'product_rev', 'feedbacks']));
    }

    public function getIndex(){

        $title = "Сервисные заявки";
        $feedbacks = Feedback::join('users', 'dv_feedbacks.user_id', '=', 'users.id')
                                ->select('dv_feedbacks.*', 'users.firstName','users.email')
                                ->paginate(15);

        foreach ($feedbacks as $key => $feedback){
            $file = DvFeedbacksFiles::where('feedback_id', $feedback->id)->get();
            $feedbacks[$key]->files = $file;
        }


        $feedbacks_count = Feedback::count();

        return view('admin.showFeedbacks', compact(['title', 'feedbacks','feedbacks_count']));
    }

    public function postIndex( Request $request ) {

        // return $request->all();
        // ajax
        if( $request->ajax() && $request->has('completed') ) {

            Feedback::where( 'id', '=', $request->get('id') )->update( ['completed' => $request->get('completed')] );
            return \Response::json( 1 );
        }

        $ids = $request->get('check');
        // если действие для удаления
        if( $request->get('action') == 'delete' )
            Feedback::destroy( $ids );

        return redirect()->back();
    }
}
