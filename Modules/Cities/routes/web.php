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


Route::group(['prefix'=>'cities','middleware' => ['permission:cities.view']],function(){
    Route::get('/', 'CitiesController@index');
});

Route::group(['prefix'=>'cities','middleware' => ['permission:cities.add']],function(){
   Route::get('/create', 'CitiesController@create');
    Route::POST('/store', 'CitiesController@store');
});
Route::group(['prefix'=>'cities','middleware' => ['permission:cities.edit']],function(){
    Route::get('/edit/{id}', 'CitiesController@edit');
    Route::POST('/update/{id}', 'CitiesController@update');
});
Route::group(['prefix'=>'cities','middleware' => ['permission:cities.delete']],function(){
    Route::get('/destroy/{id}', 'CitiesController@destroy');
});


