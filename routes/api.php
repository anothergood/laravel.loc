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
Route::post('register','RegisterController@store');
Route::post('login','LoginController@store');
// Route::post('/logout','LogoutController@????')->middleware('auth:api');

Route::apiResource('friends','FriendController')->middleware('auth:api');
Route::prefix('friends')->group(function(){
    Route::post('invite','FriendController@invitefriend')->middleware('auth:api');
    Route::post('approve','FriendController@approvefriend')->middleware('auth:api');
});

Route::apiResource('likes','LikeController')->middleware('auth:api');

Route::apiResource('comments','CommentController')->middleware('auth:api');


Route::apiResource('posts','PostController')->middleware('auth:api');

Route::get('friendsposts','PostController@friendsposts')->middleware('auth:api');
Route::get('myposts','PostController@myposts')->middleware('auth:api');

Route::prefix('posts')->group(function(){

  Route::prefix('myposts')->group(function(){
    Route::get('/{id}','PostController@mypost')->middleware('auth:api');
    Route::get('/{id}/comments','CommentController@mypostcomments')->middleware('auth:api');
  });
});


  //   Route::get('/{id}','PostController@myposts')->middleware('auth:api');
  //   Route::post('/audio','AttachmentAudioController@store');
  //   Route::post('/video','AttachmentVideoController@store');
  //   Route::post('/document','AttachmentDocumentController@store');


// Route::middleware('auth:api')->prefix('attachments')->group(function(){
//     Route::post('/image','AttachmentController@store');
// //     Route::post('/audio','AttachmentAudioController@store');
// //     Route::post('/video','AttachmentVideoController@store');
// //     Route::post('/document','AttachmentDocumentController@store');
// });



// Route::group(['prefix' => 'posts'], function(){
    // Route::post('/store_post','PostController@store')->middleware('auth:api');
    // Route::get('/','PostController@index');
    // Route::get('/{post}','PostController@show');

//     Route::get('foo', function () {
//         return 'Hello World';
//     });
// });
