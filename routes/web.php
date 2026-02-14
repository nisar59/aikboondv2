<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes(['register'=>false]);
Route::any('/logout', 'Auth\LoginController@logout');

Route::group(['middleware'=>'auth'], function(){
    Route::get('/', 'HomeController@index');
    Route::get('/dashboard', 'HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('states','HomeController@fetchStates');
    Route::post('cities','HomeController@fetchCity');
//    Route::POST('send-code', 'HomeController@verificationCode');

});
