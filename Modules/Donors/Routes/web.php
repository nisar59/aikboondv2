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


Route::group(['prefix'=>'donors','middleware' => ['permission:donors.view']],function(){
    Route::get('/', 'DonorsController@index');
});

Route::group(['prefix'=>'donors','middleware' => ['permission:donors.add']],function(){
    Route::get('/create', 'DonorsController@create');
    Route::POST('/store', 'DonorsController@store');
});
Route::group(['prefix'=>'donors','middleware' => ['permission:donors.edit']],function(){
    Route::get('/edit/{id}', 'DonorsController@edit');
    Route::POST('/update/{id}', 'DonorsController@update');
});
Route::group(['prefix'=>'donors','middleware' => ['permission:donors.delete']],function(){
    Route::get('/destroy/{id}', 'DonorsController@destroy');
});

