<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class NotifyController extends Controller
{
    function list() {
        $notifications = DB::table('notifications')
            ->join('customers', 'notifications.customer_id', '=', 'customers.id')
            ->select('notifications.id','notifications.notification','customers.firstname', 'customers.lastname')
            ->get();
        return $notifications;
    }
    
    function add(Request $req) {
        $notifications=new Notifications;
        $notifications->notification=$req->input('notification');
        $notifications->customer_id = $req->input('customer_id');
        $notifications->save();
        return response()->json($notifications);
    }
    
    function delete(Request $req) {
        Notification::find($req->input('id'))->delete();
    }

    function listmsg() {
        $messages = DB::table('messages')
            ->select('messages.id','messages.customer_id','messages.name',
                     'messages.email','messages.question')
            ->get();
        return $messages;
    }
    
    function addmsg(Request $req) {
        $messages=new Messages;
        $messages->customer_id = $req->input('customer_id');
        $messages->name = $req->input('name');
        $messages->email = $req->input('email');
        $messages->question = $req->input('question');
        $messages->save();
        return response()->json($messages);
    }
    
    function deletemsg(Request $req) {
        Message::find($req->input('id'))->delete();
    }
}
