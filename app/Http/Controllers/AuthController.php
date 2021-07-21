<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    function list()
    {
        $users = DB::table('customers')
            ->join('users', 'customers.user_id', '=', 'users.id')
            ->select('customers.*', 'users.*')
            ->get();
        return $users;
    }

    function getuser(Request $req) {
        $user = DB::table('customers')
            ->join('users', 'customers.user_id', '=', 'users.id')
            ->where('customers.id','=',$req->input('customer_id'))
            ->select('customers.firstname', 'customers.lastname', 'users.name',
                    'users.email')
            ->get();
        return $user;
    }

    function update(Request $req) {
        $customer = Customer::find($req->input('customer_id'));
        $user = User::find($customer->user_id);

        $customer->firstname = $req->input('firstname');
        $customer->lastname = $req->input('lastname');

        $user->name = $req->input('name');
        $user->email = $req->input('email');

        $user->save();
        $customer->save();
        
        return response()->json([
            'message' => 'Successfully updated user!',
            'customer' => $customer,
            'user' => $user
        ], 201);
    }

    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();
        $customer = new Customer;
        $customer->firstname = $request->input('firstname');
        $customer->lastname = $request->input('lastname');
        $customer->user_id = $user->id;
        $customer->save();
        return response()->json([
            'message' => 'Successfully created user!',
            'customer' => $customer,
            'user' => $user
        ], 201);
    }
  
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $customer = Customer::where('user_id',$user->id)->firstOrFail();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'customer' => $customer,
            'user' => $user,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}