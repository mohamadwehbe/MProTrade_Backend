<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use App\Models\Software;

class SoftwareController extends Controller
{
    function list() {
        $softwares = DB::table('softwares')
            ->join('customers', 'softwares.customer_id', '=', 'customers.id')
            ->join('users', 'customers.user_id', '=', 'users.id')
            ->select('softwares.id','softwares.name','softwares.price',
                     'softwares.description', 'softwares.image_url',
                     'customers.firstname', 'customers.lastname','users.email')
            ->get();
        return $softwares;
    }
    
    function add(Request $req) {
        $software=new Software();
        $software->name=$req->input('name');
        $software->description=$req->input('description');
        $software->price=$req->input('price');
        $software->customer_id = $req->input('customer_id');
        if ($req->hasFile('image')) {
            $destination = 'public/images';
            $image = $req->file('image');
            $image_name = $image->getClientOriginalName();
            $path = $image->storeAs($destination,$image_name);
            $software->image_url=$image_name;
        }
        $software->save();
        return response()->json($software);
    }
    
    function delete(Request $req) {
        Software::find($req->input('id'))->delete();
    }
}
