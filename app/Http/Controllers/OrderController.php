<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    function list()
    {
        $orders = DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('users', 'customers.user_id', '=', 'users.id')
            ->select('customers.firstname','customers.lastname', 'users.email',
                     'orders.city','orders.area','orders.street','orders.building','orders.floor')
            ->get();
        return $orders;
    }

    function addorder (Request $req)
    {
        $order = new Order;
        $order->customer_id = $req->input('customer_id');
        $order->city = $req->input('city');
        $order->area = $req->input('area');
        $order->street = $req->input('street');
        $order->building = $req->input('building');
        $order->floor = $req->input('floor');
        $order->save();

        return response()->json($order);
    }
}
