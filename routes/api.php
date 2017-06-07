<?php

use Illuminate\Http\Request;

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

Route::get('users', 'UserController@index');

Route::post('users', 'UserController@store');

Route::get('users/{user}', 'UserController@show');

Route::delete('users/{user}', 'UserController@destroy');


Route::get('tasks', 'TaskController@index');

Route::post('tasks', 'TaskController@store');

Route::get('tasks/{task}', 'TaskController@show');

Route::patch('tasks/{task}', 'TaskController@update');

Route::delete('tasks/{task}', 'TaskController@destroy');


Route::patch('tasks/{task}/completed', 'TaskCompletionController@update');
