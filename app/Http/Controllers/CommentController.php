<?php

namespace App\Http\Controllers;

use App\User;
use App\Post;
use App\Comment;
use App\Attachment;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCommentRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class CommentController extends Controller
{
    public function myPostComments(Request $request, Post $post)
    {
        if ($post->user_id == $request->user()->id) {
            $comments = Comment::where('post_id', '=', $post->id)->paginate(2);
            return response($comments);
        } else {
            return response(['message' => 'Forbidden'], 403);
        }
    }

    public function store(StoreCommentRequest $request)
    {
        $user = $request->user();
        $post_user_id = Post::find($request->post_id)->user_id;
        $post_user = User::find($post_user_id);
        $initiator_user = $user->users()->where('user_id', '=', $post_user_id)->where('status', '=', 'approved')->exists();
        $initiator_post_user = $post_user->users()->where('user_id', '=', $request->user()->id)->where('status', '=', 'approved')->exists();

        if ($initiator_user or $initiator_post_user or $request->user()->id == $post_user_id) {
            $comment = new Comment;
            $comment->body = $request->body;
            $comment->user_id = $request->user()->id;
            $comment->post_id = $request->post_id;
            $comment->save();

            if ($request->hasFile('file')) {
                $path = Storage::putFile('attachments', new File($request->file), 'public');
                $mime = $request->file->getMimeType();
                $attachment = new Attachment;
                $attachment->file_path = $path;
                $attachment->file_type = $mime;
                $comment->attachments()->save($attachment);
                return response(['comment' => $comment,
                                 'attachment' => $attachment]);
            }
            return response($comment);
        } else {
            return response(['message' => 'only friends can post comments'], 403);
        }
    }
}
