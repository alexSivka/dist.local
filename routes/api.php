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



Route::get('/books', 'BookController@index');
Route::get('/book/{id}', 'BookController@book');

Route::group(['middleware' => 'auth:api'], function(){

	Route::get('/edit/{id}', 'BookController@edit');
	Route::post('/book/save', 'BookController@save');
	Route::delete('/book/{id}', 'BookController@remove');

	Route::get('/tags', 'TagController@index');
	Route::get('/tag/{id}', 'TagController@tag');
	Route::post('/tag/save', 'TagController@save');
	Route::delete('/tag/{id}', 'TagController@remove');
});






