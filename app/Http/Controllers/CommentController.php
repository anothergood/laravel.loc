<?php

namespace App\Http\Controllers;

use App\Comment;
use App\UserUser;

use App\Friend;
use App\Attachment;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCommentRequest;

class CommentController extends Controller
{
    public function mypostcomments(Request $request, $id)
    {
      $comments = Comment::where('post_id','=', $id)->paginate(2);
      return $comments;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCommentRequest $request)
    {
      $user_post_id = Post::find($request->post_id)->user_id;

      $v1 = UserUser::where('user_initiator_id', '=', $request->user()->id)->where('user_id','=', $user_post_id)->where('status','=', 'approved')->exists();
      $v2 = UserUser::where('user_initiator_id', '=', $user_post_id)->where('user_id','=', $request->user()->id)->where('status','=', 'approved')->exists();

      if ($v1 or $v2) {

          $comment = new Comment;
          $comment->title = $request->title;
          $comment->user_id = $request->user()->id;
          $comment->post_id = $request->post_id;
          $comment->save();
          // return $comment;

          //прикрепление attachments
          if ($request->hasFile('file')){
              $file = $request->file('file');
              $path = $request->file->store('public/attachments');
              $mime = $request->file('file')->getMimeType();

              $link = asset('storage/'.$path);  //????????????

              $attachment = new Attachment;
              $attachment->file_path = $path;
              $attachment->file_type = $mime;
              $attachment->entity_id = $comment->id;
              $attachment->entity_type = 'comments';

              $attachment->save();

              $value = array('comment' => $comment,
                             'attachment' => $attachment,
                            );
              return $value;
          }
          $value = array('comment' => $comment);
          return $value;

      } else {
        return $value = array('message' => 'Only friends can post comments.');
      }







      // $user_post_id = Post::find($request->post_id)->user_id;
      //
      // $v1 = Friend::where('user_initiator_id', '=', $request->user()->id)->where('user_id','=', $user_post_id)->where('status','=', 'approved')->exists();
      // $v2 = Friend::where('user_initiator_id', '=', $user_post_id)->where('user_id','=', $request->user()->id)->where('status','=', 'approved')->exists();
      //
      // if ($v1 or $v2) {
      //
      //     $comment = new Comment;
      //     $comment->title = $request->title;
      //     $comment->user_id = $request->user()->id;
      //     $comment->post_id = $request->post_id;
      //     $comment->save();
      //     // return $comment;
      //
      //     //прикрепление attachments
      //     if ($request->hasFile('file')){
      //         $file = $request->file('file');
      //         $path = $request->file->store('public/attachments');
      //         $mime = $request->file('file')->getMimeType();
      //
      //         $link = asset('storage/'.$path);  //????????????
      //
      //         $attachment = new Attachment;
      //         $attachment->file_path = $path;
      //         $attachment->file_type = $mime;
      //         $attachment->entity_id = $comment->id;
      //         $attachment->entity_type = 'comments';
      //
      //         $attachment->save();
      //
      //         $value = array('comment' => $comment,
      //                        'attachment' => $attachment,
      //                       );
      //         return $value;
      //     }
      //     $value = array('comment' => $comment);
      //     return $value;
      //
      // } else {
      //   return $value = array('message' => 'Only friends can post comments.');
      // }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
