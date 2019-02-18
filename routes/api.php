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

Route::get('user-self', 'UserController@userSelf')->middleware('auth:api')->middleware('verify');

Route::post('register', 'RegisterController@store');
Route::get('user/verify/{token}', 'RegisterController@verifyUser');

Route::post('login', 'LoginController@store');
// Route::post('logout','LogoutController@????')->middleware('auth:api');

Route::group(['prefix' => 'friends','middleware' => 'auth:api'], function () {
    Route::post('invite', 'FriendController@inviteFriend');
    Route::post('approve', 'FriendController@approveFriend');
});

Route::group(['prefix' => 'posts','middleware' => 'auth:api'], function () {
    Route::apiResource('comments', 'CommentController');
    Route::apiResource('', 'PostController');
    Route::apiResource('like', 'LikeController');
    Route::get('friends-posts', 'PostController@friendsPosts');
    Route::group(['prefix' => 'my-posts'], function () {
        Route::get('', 'PostController@myPosts');
        Route::get('{post}', 'PostController@myPost');
        Route::get('{post}/comments', 'CommentController@myPostComments');
    });
});
