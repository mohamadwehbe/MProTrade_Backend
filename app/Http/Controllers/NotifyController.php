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
        $notification=new Notification;
        $notification->notification=$req->input('notification');
        $notification->customer_id = $req->input('customer_id');
        $notification->save();
        return response()->json($notification);
    }
    
    function delete(Request $req) {
        Notification::find($req->input('id'))->delete();
    }

    function listmsg() {
        $messages = DB::table('messages')
            ->select('messages.id','messages.name','messages.email','messages.question')
            ->get();
        return $messages;
    }
    
    function addmsg(Request $req) {
        $message=new Message;
        $message->name = $req->input('name');
        $message->email = $req->input('email');
        $message->question = $req->input('question');
        $message->save();
        return response()->json($message);
    }
    
    function deletemsg(Request $req) {
        Message::find($req->input('id'))->delete();
    }
}
