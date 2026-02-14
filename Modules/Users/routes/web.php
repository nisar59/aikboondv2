<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix'=>'users','middleware' => ['permission:users.view']],function(){
	Route::get('/', 'UsersController@index');
	Route::get('/registered-people', 'UsersController@people');
});

Route::group(['prefix'=>'users','middleware' => ['permission:users.add']],function(){
	Route::get('/create', 'UsersController@create');
	Route::POST('/store', 'UsersController@store');

});
Route::group(['prefix'=>'users','middleware' => ['permission:users.edit']],function(){
	Route::get('/edit/{id}', 'UsersController@edit');
	Route::POST('/update/{id}', 'UsersController@update');
	Route::POST('/status/{id}', 'UsersController@status');
});
Route::group(['prefix'=>'users','middleware' => ['permission:users.delete']],function(){
	Route::get('/destroy/{id}', 'UsersController@destroy');
});

