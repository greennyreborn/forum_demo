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

Route::get('/', 'HomeController@index')->name('home');

Route::group([
    'prefix' => 'user'
], function () {
    Route::post('', 'UserController@create');
});

Route::group([
    'prefix' => 'topic'
], function () {
    Route::get('list', 'TopicController@getList');
    Route::get('{topic_id}', 'TopicController@detail');
    Route::post('user/{uid}', 'TopicController@create');
    Route::put('{topic_id}', 'TopicController@edit');
});

Route::group([
    'prefix' => 'post'
], function () {
    Route::post('topic/{topic_id}', 'PostController@create');
    Route::put('{post_id}', 'PostController@edit');
});