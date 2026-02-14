<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix'=>'/', 'middleware'=>['api']],function(){
    Route::post('send-code', 'API\AuthController@verificationCode');
});

Route::group(['prefix'=>'auth', 'middleware'=>['api']],function(){
    Route::post('login', 'API\AuthController@login');
    Route::get('refresh', 'API\AuthController@refresh');
});
