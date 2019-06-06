<?php
/**
 * Created by PhpStorm.
 * User: Dvacom
 * Date: 06.06.2017
 * Time: 12:29
 */

namespace App\Http\Controllers\Frontend;

use App\Models\Notification;
use App\User;
use DateTime;
use DateInterval;
use Mail;

class NotificationController extends BaseController
{
    public function index(){
        $notifications = Notification::all();

        foreach ($notifications as $notification){
            $curentDate = new DateTime();
            $lastSend = explode(' ', $notification->last_send);

            if(count($lastSend) > 0){
                $lastSend = $lastSend[0];
            }
            $date = new DateTime($lastSend);
            $date->add(new DateInterval("P".$notification->interval."D"));

            if($notification->repeat == 1 || $notification->count == 0){
                $nowDate = $curentDate->format('Y-m-d');
                $nextSendDate = $date->format('Y-m-d');
                if($nowDate >= $nextSendDate){
                    $user = User::whereId($notification->user_id)->first();
                    Notification::whereId($notification->id)->update(['last_send' => $nowDate, 'count' => 1]);
                    if($email = $user->email){
                        Mail::send('emails.cron', ['user' => $user, 'site' => $_SERVER['HTTP_HOST']], function ($m) use ($user) {
                            $m->from('noreply@'.$_SERVER['HTTP_HOST'], $_SERVER['HTTP_HOST']);
                            $m->to($user->email, $user->firstName)->subject('Напоминание о смене линз!');
                        });
                    }
                }
            }

        }
    }
}