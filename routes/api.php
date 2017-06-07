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
 * send email confirmation
 */
Route::post('email-confirmations', 'SendEmailConfirmationController@store');


/*
 * confirm email
 */
Route::patch('users/email', 'ConfirmEmailController@update');


/*
 * tasks
 */
Route::get('tasks', 'TaskController@index')
    ->middleware('auth:api');

Route::post('tasks', 'TaskController@store')
    ->middleware('auth:api');

Route::get('tasks/{task}', 'TaskController@show')
    ->middleware('auth:api');

Route::patch('tasks/{task}', 'TaskController@update')
    ->middleware('auth:api');

Route::delete('tasks/{task}', 'TaskController@destroy')
    ->middleware('auth:api');


/*
 * tasks completion
 */
Route::patch('tasks/{task}/completed', 'TaskCompletionController@update')
    ->middleware('auth:api');
