<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Pagination\Paginator;

class NotificationController extends Controller
{
    public function getNotifications(){
        $notifications = DB::table('user_notifications')
            ->where('userlist', 'LIKE', '%'.Auth::id().'%')
            ->orderBy('created_on', 'desc')
            ->paginate(5);
        DB::table('user_notifications')
            ->where('userlist', 'LIKE', '%'.Auth::id().'%')
            ->update(['posted'=>1]);
        $count = DB::table('user_notifications')
            ->where('userlist', 'LIKE', '%'.Auth::id().'%')
            ->where('seen','=',0)
            ->where('posted','=',1)
            ->orderBy('created_on', 'desc')
            ->get();
        $view = view('notification.notifications',['notifications'=>$notifications])->render();
        return response()->json(array('count' => count($count), 'html'=>$view));
    }

    public function seeNotifications(Request $request){
        DB::table('user_notifications')
            ->where('userlist', 'LIKE', '%'.Auth::id().'%')
            ->where('seen', '=', 0)
            ->update(['seen'=>1]);
    }

    public function checkNotifications(){
        $notifications = DB::table('user_notifications')
            ->where('userlist', 'LIKE', '%'.Auth::id().'%')
            ->where('seen','=',0)
            ->where('posted','=',0)
            ->orderBy('created_on', 'desc')
            ->get();
        DB::table('user_notifications')
            ->where('userlist', 'LIKE', '%'.Auth::id().'%')
            ->update(['posted'=>1]);
        $count = DB::table('user_notifications')
            ->where('userlist', 'LIKE', '%'.Auth::id().'%')
            ->where('seen','=',0)
            ->where('posted','=',1)
            ->orderBy('created_on', 'desc')
            ->get();
        $groupRequests = 0;
        $meetingRequests = 0;
        foreach ($notifications as $notification){
            $notification->elapsed = \Carbon\Carbon::createFromTimeStamp(strtotime($notification->created_on))->diffForHumans();
            if ($notification->type == 'group'){
                $notification->html = '<div class="notification-block" style="background: mintcream;"><p class="no-margin no-padding"><span class="col-xs-2" style="padding-top: 10px;"><i class="fa fa-users fa-3x"></i></span><span class="col-xs-10 text-left">'.$notification->notification_content.'<br><i class="fa fa-clock-o"></i> <span style="color: #1abc9c;">'.$notification->elapsed.'</span></span></p><hr class="no-margin"></div>';
            } elseif ($notification->type == 'group-request'){
                $notification->html = '<a href="'.url('/group/requests').'"><div  class="notification-block" style="background: mintcream;"><p class="no-margin no-padding"><span class="col-xs-2" style="padding-top: 10px;"><i class="fa fa-users fa-3x"></i></span><span class="col-xs-10 text-left">'.$notification->notification_content.'<br><i class="fa fa-clock-o"></i> <span style="color: #1abc9c;">'.$notification->elapsed.'</span></span></p><hr class="no-margin"></div></a>';
            } elseif ($notification->type == 'meeting-request'){
                $notification->html = '<a href="'.url('/meetings/requests').'"><div class="notification-block" style="background: mintcream;"><p class="no-margin no-padding"><span class="col-xs-2" style="padding-top: 10px;"><i class="fa fa-handshake-o fa-3x"></i></span><span class="col-xs-10 text-left">'.$notification->notification_content.'<br><i class="fa fa-clock-o"></i> <span style="color: #1abc9c;">'.$notification->elapsed.'</span></span></p><hr class="no-margin"></div></a>';
            } else {
                $notification->html = '<div class="notification-block" style="background: mintcream;"><p class="no-margin no-padding"><span class="col-xs-2" style="padding-top: 10px;"><i class="fa fa-handshake-o fa-3x"></i></span><span class="col-xs-10 text-left">'.$notification->notification_content.'<br><i class="fa fa-clock-o"></i> <span style="color: #1abc9c;">'.$notification->elapsed.'</span></span></p><hr class="no-margin"></div>';
            }
        }
        return response()->json(array('notifications'=>$notifications, 'count'=>count($count),'meeting'=>$meetingRequests,'group'=>$groupRequests));
    }
}
