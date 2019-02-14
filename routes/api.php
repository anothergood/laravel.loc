<?php

use Illuminate\Http\Request;
use App\User;

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
Route::post('register', 'RegisterController@store');
Route::post('login', 'LoginController@store');
// Route::post('/logout','LogoutController@????')->middleware('auth:api');

Route::group(['prefix' => 'friends','middleware' => 'auth:api'], function () {
    Route::post('invite', 'FriendController@inviteFriend');
    Route::post('approve', 'FriendController@approveFriend');
});

Route::group(['prefix' => 'posts','middleware' => 'auth:api'], function () {
    Route::apiResource('comments', 'CommentController');
    Route::apiResource('/', 'PostController');
    Route::apiResource('like', 'LikeController');
    Route::get('friends-posts', 'PostController@friendsPosts');
    Route::group(['prefix' => 'my-posts'], function () {
        Route::get('/', 'PostController@myPosts');
        Route::get('/{post}', 'PostController@myPost');
        Route::get('/{post}/comments', 'CommentController@myPostComments');
    });
});
