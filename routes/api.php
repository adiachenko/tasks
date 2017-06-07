<?php

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

/*
 * access tokens
 */
Route::post('auth/token', 'IssueAccessTokenController');


/*
 * users
 */
Route::get('users', 'UserController@index')
    ->middleware('auth:api');

Route::post('users', 'UserController@store');

Route::get('users/{user}', 'UserController@show')
    ->middleware('auth:api');

Route::delete('users/{user}', 'UserController@destroy')
    ->middleware('auth:api', 'can:delete,user');


/*
 * tasks
 */
Route::get('tasks', 'TaskController@index');

Route::post('tasks', 'TaskController@store');

Route::get('tasks/{task}', 'TaskController@show');

Route::patch('tasks/{task}', 'TaskController@update');

Route::delete('tasks/{task}', 'TaskController@destroy');


/*
 * tasks completion
 */
Route::patch('tasks/{task}/completed', 'TaskCompletionController@update');
