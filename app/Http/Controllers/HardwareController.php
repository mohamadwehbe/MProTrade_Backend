<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use App\Models\Hardware;

class HardwareController extends Controller
{
    function list() {
        $hardwares = DB::table('hardwares')
            ->join('customers', 'hardwares.customer_id', '=', 'customers.id')
            ->join('users', 'customers.user_id', '=', 'users.id')
            ->select('hardwares.id','hardwares.name','hardwares.price',
                     'hardwares.description', 'hardwares.image_url',
                     'customers.firstname', 'customers.lastname','users.email')
            ->get();
        return $hardwares;
    }
    
    function add(Request $req) {
        $hardware=new Hardware();
        $hardware->name=$req->input('name');
        $hardware->description=$req->input('description');
        $hardware->price=$req->input('price');
        $hardware->customer_id = $req->input('customer_id');
        if ($req->hasFile('image')) {
            $destination = 'public/images';
            $image = $req->file('image');
            $image_name = $image->getClientOriginalName();
            $path = $image->storeAs($destination,$image_name);
            $hardware->image_url=$image_name;
        }
        $hardware->save();
        return response()->json($hardware);
    }
    
    function delete(Request $req) {
        Hardware::find($req->input('id'))->delete();
    }
}
