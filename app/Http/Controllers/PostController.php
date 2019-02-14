<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class PostController extends Controller
{
    public function myPosts(Request $request)
    {
        $posts = $request->user()->posts()->paginate(1);
        return $posts;
    }

    public function myPost(Request $request, Post $post)
    {
        if ($post->user_id == $request->user()->id) {
            return response($post);
        } else {
            return response(['message' => 'Forbidden'], 403);
        }
    }

    public function friendsPosts(Request $request)
    {
        $user = $request->user();
        $friend_id = [];
        foreach ($user->users as $friend) {
            $friend_id[] = $friend->id;
        }
        $posts = Post::whereIn('user_id', $friend_id)->paginate(2);
        return response($posts);
    }

    public function index()
    {
        $posts = Post::paginate(5);
        return $posts;
    }

    public function create()  //возвр. view (html) (web.php)
    {
        //(web.php)
    }

    public function store(StorePostRequest $request)
    {
        $post = new Post;
        $post->title = $request->title;
        $post->body = $request->body;

        $post->user_id = $request->user()->id;
        $post->save();

        if ($request->hasFile('file')) {
            $path = Storage::putFile('attachments', new File($request->file), 'public');
            $mime = $request->file->getMimeType();

            $attachment = new Attachment;
            $attachment->file_path = $path;
            $attachment->file_type = $mime;
            $post->attachments()->save($attachment);

            return response(['post' => $post,
                             'attachment' => $attachment
                           ]);
        }
        return response($post);
    }

    public function show(Request $request, $id)
    {
        $post = Post::find($id);
    }



    public function edit($id) //view на изменение объекта (web.php)
    {
        //(web.php)
    }

    public function update(StorePostRequest $request, $id)
    {
        $post = Post::find($id);
        $post->title = $request->title;
        $post->save();
        return $post;
    }

    public function destroy($id)
    {
        Post::destroy($id);
    }

    public function dest($id)
    {
        Post::destroy($id);
    }
}
