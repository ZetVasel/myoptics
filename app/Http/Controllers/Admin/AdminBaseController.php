<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\Feedback;

class AdminBaseController extends Controller
{
	public function __construct(){

		$new_orders = Orders::whereStatus(0)->count();
		$feedback_count = Feedback::all()->count();
		$new_feedbacks = Feedback::whereCompleted(0)->count();

		view()->share(compact(['new_orders','feedback_count', 'new_feedbacks']));
	}
}
