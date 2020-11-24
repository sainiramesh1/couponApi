<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::post('/coupon-generate', 'Api\CouponController@generate_coupon');
Route::get('/get-coupons', 'Api\CouponController@get_coupons');
Route::post('check-coupon-validity', 'Api\CouponController@check_validity');
