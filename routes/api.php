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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', 'UserController@register');
Route::post('register/folder', 'UserController@video_folder');
Route::post('login', 'UserController@authenticate');
Route::get('open', 'UserController@open');
//Route::get('findUser', 'UserControlller@findUser');
Route::get('findUserPerEmail', 'UserController@findUserPerEmail');
Route::post('video', 'VideoController@store');
Route::get('loadVideos', 'VideoController@loadVideos');
Route::get('countVideos', 'VideoController@countVideos');
Route::get('loadIndexVideo', 'VideoController@loadIndexVideo');
Route::get('databaseVideosDetail', 'VideoController@databaseVideosDetail');
//Route::delete('databaseDeleteVideo/{id}', 'VideoController@databaseDeleteVideo');
//Route::delete('articles/{id}', 'ArticleController@delete');
Route::delete('databaseDeleteVideo', 'VideoController@databaseDeleteVideo');
Route::delete('serverDeleteVideo', 'VideoController@serverDeleteVideo');

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('user', 'UserController@getAuthenticatedUser');
    Route::get('closed', 'UserController@closed');
});
