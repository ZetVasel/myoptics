<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Http\Requests;
use App\Http\Requests\NotificationsRequest;
use App\Http\Controllers\Admin\AdminBaseController;
use DateTime;
use DateInterval;

class AdminNotificationsController extends AdminBaseController {

	public function getIndex()
	{
		$title = 'Напоминания для пользователей';
		$notify_count = Notification::count();
		$notify = Notification::join('users', 'notifications.user_id', '=', 'users.id')
                                ->select('notifications.*', 'users.firstName','users.email')
                                ->latest()->paginate(15);
		return view('admin.showNotifications', compact(['notify','notify_count', 'title']));
	}

	public function postIndex( Request $request ) {

		$ids = $request->get('check');
		// если действие для удаления
		if( $request->get('action') == 'delete' ) {
			// удалим из бд
			Notification::destroy( $ids );
		}
		return redirect()->back();
	}

}