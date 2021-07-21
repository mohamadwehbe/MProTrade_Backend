<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Hardwarecart;
use App\Models\Softwarecart;

class CartController extends Controller
{
    function list(Request $req) {
        $hardcarts = DB::table('hardwarecarts')
            ->join('hardwares', 'hardwarecarts.hardware_id', '=', 'hardwares.id')
            ->join('customers','hardwarecarts.customer_id', '=', 'customers.id')
            ->select('hardwares.id','hardwares.name','hardwares.price',
                     'hardwares.description', 'hardwares.image_url',
                     'customers.firstname', 'customers.lastname')
            ->where('hardwarecarts.customer_id',$req->input('customer_id'))
            ->get();
        $softcarts = DB::table('softwarecarts')
            ->join('softwares', 'softwarecarts.software_id', '=', 'softwares.id')
            ->join('customers','softwarecarts.customer_id', '=', 'customers.id')
            ->select('softwares.id','softwares.name','softwares.price',
                     'softwares.description', 'softwares.image_url',
                     'customers.firstname', 'customers.lastname')
            ->where('softwarecarts.customer_id',$req->input('customer_id'))
            ->get();
        return response()->json([
            'hardcarts' => $hardcarts,
            'softcarts' => $softcarts
            ]);
    }

    function addhardcart(Request $req) {
        $hardcart = new Hardwarecart;
        $hardcart->customer_id = $req->input('customer_id');
        $hardcart->hardware_id = $req->input('hardware_id');
        $hardcart->save();
        return response()->json($hardcart);
    }

    function addsoftcart(Request $req) {
        $softcart = new Softwarecart;
        $softcart->customer_id = $req->input('customer_id');
        $softcart->software_id = $req->input('software_id');
        $softcart->save();
        return response()->json($softcart);
    }

    function deletehardcart(Request $req) {
        DB::table('hardwarecarts')
            ->where('customer_id', $req->input('customer_id'))
            ->delete();
    }

    function deletesoftcart(Request $req) {
        DB::table('softwarecarts')
            ->where('customer_id', $req->input('customer_id'))
            ->delete();
    }

    // function remove(Request $req) {
    //     $hardcart=Hardwarecart::find($req->input('id'));
    //     if($hardcart)
    //         $hardcart->delete();
    //     $softcart=Softwarecart::find($req->input('id'));
    //     if($softcart)
    //         $softcart->delete();
    // }

    function removefromcart(Request $req) {
        DB::table('softwarecarts')
        ->join('softwares', 'softwarecarts.software_id', '=', 'softwares.id')
        ->join('customers','softwarecarts.customer_id', '=', 'customers.id')
        ->where('softwarecarts.customer_id', $req->input('customer_id'))
        ->where('softwares.name', $req->input('name'))
        ->delete();

        DB::table('hardwarecarts')
        ->join('hardwares', 'hardwarecarts.hardware_id', '=', 'hardwares.id')
        ->join('customers','hardwarecarts.customer_id', '=', 'customers.id')
        ->where('hardwarecarts.customer_id', $req->input('customer_id'))
        ->where('hardwares.name', $req->input('name'))
        ->delete();
    }
}
