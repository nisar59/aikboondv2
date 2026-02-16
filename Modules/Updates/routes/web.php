<?php

use Illuminate\Support\Facades\Route;
use Modules\Updates\App\Http\Controllers\UpdatesController;

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

Route::group(['prefix'=>'updates','middleware' => ['permission:updates.view']],function(){
    Route::get('/', 'UpdatesController@index');
});

Route::group(['prefix'=>'updates','middleware' => ['permission:updates.add']],function(){
    Route::get('/create', 'UpdatesController@create');
    Route::POST('/store', 'UpdatesController@store');

});
Route::group(['prefix'=>'updates','middleware' => ['permission:updates.edit']],function(){
    Route::get('/edit/{id}', 'UpdatesController@edit');
    Route::POST('/update/{id}', 'UpdatesController@update');
    Route::get('/status/{id}', 'UpdatesController@status');
});
Route::group(['prefix'=>'updates','middleware' => ['permission:updates.delete']],function(){
    Route::get('/destroy/{id}', 'UpdatesController@destroy');
});

