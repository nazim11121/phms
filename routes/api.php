<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->post('/profile-information/update', 'Api\UserController@informationUpdate');
Route::middleware('auth:sanctum')->post('/profile-password/update', 'Api\UserController@passwordChange');
Route::middleware('auth:sanctum')->post('/profile-photo/update', 'Api\UserController@profilePhoto');
Route::middleware('auth:sanctum')->post('/delivery-address', 'Api\UserController@deliveryAddress');
Route::middleware('auth:sanctum')->post('/pickup-address', 'Api\UserController@pickupAddress');
Route::middleware('auth:sanctum')->post('/order', 'Api\UserController@order');

Route::group([

    'middleware' => 'api',

], function () {
	// $controller_path = 'App\Http\Controllers';
	// Route::post('login', $controller_path . '\Api\UserController@login');
	Route::post('mobile-registration', 'Api\UserController@mobileOtp');
    Route::post('otp-verify', 'Api\UserController@otpVerify');
    Route::post('registration-form', 'Api\UserController@registration');
    Route::post('login', 'Api\UserController@login');
});
