<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HardwareController;
use App\Http\Controllers\SoftwareController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class,'login']);
    Route::post('signup', [AuthController::class,'signup']);
    Route::post('getuser', [AuthController::class,'getuser']);
    Route::post('updateuser', [AuthController::class,'update']);
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::post('logout', [AuthController::class,'signup']);
        Route::post('user', [AuthController::class,'user']);
    });
});

Route::get('listuser', [AuthController::class,'list']);

Route::get('listhardware', [HardwareController::class,'list']);
Route::post('addhardware', [HardwareController::class,'add']);
Route::delete('deletehardware', [HardwareController::class,'delete']);

Route::get('listsoftware', [SoftwareController::class,'list']);
Route::post('addsoftware', [SoftwareController::class,'add']);
Route::delete('deletesoftware', [SoftwareController::class,'delete']);

Route::post('listcart', [CartController::class,'list']);
Route::post('addsoftcart', [CartController::class,'addsoftcart']);
Route::post('addhardcart', [CartController::class,'addhardcart']);
Route::post('deletesoftcart', [CartController::class,'deletesoftcart']);
Route::post('deletehardcart', [CartController::class,'deletehardcart']);
Route::post('remove',[CartController::class,'removefromcart']);

Route::post('addorder', [OrderController::class,'addorder']);
Route::get('listorder', [OrderController::class,'list']);