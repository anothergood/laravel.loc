<?php

namespace App\Http\Controllers;

use App\Comment;
use App\UserUser;
use App\Attachment;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;

class PostController extends Controller
{
    public function myposts(Request $request)
    {
        // $posts = Post::where('user_id','=', $request->user()->id)->paginate(2);
        $posts = $request->user()->posts()->paginate(1);
        return $posts;
    }

    public function friendsposts(Request $request)
    {
        $user = $request->user();
        $friend_id = [];
        foreach ($user->users as $friend) {
            $friend_id[] = $friend->id;
        }
        $posts = Post::whereIn('user_id', $friend_id)->paginate(2);
        return $posts;


        // return $friends;

        // $user = $request->user();
        // $value = [];
        // foreach ($user->users as $friend) {
        //   $value[] = $friend->posts;
        // }
        // return $value;
    }

    public function index() //получение всех объектов +лимиты
    {
      $posts = Post::paginate(5);
      return $posts;
    }

    public function create()  //возвр. view (html) (web.php)
    {
        //(web.php)
    }

    public function store(StorePostRequest $request) // создает новый объект в бд
    {
        //создание поста
        $post = new Post;
        $post->title = $request->title;
        $post->user_id = $request->user()->id;
        $post->save();
        // return $post;

        //прикрепление attachments
        if ($request->hasFile('file')){
            $file = $request->file('file');
            $path = $request->file->store('public/attachments');
            $mime = $request->file('file')->getMimeType();

            $link = asset('storage/'.$path);  //????????????

            $attachment = new Attachment;
            $attachment->file_path = $path;
            $attachment->file_type = $mime;
            $attachment->entity_id = $post->id;
            $attachment->entity_type = 'posts';

            $attachment->save();

            $value = array('post' => $post,
                           'attachment' => $attachment,
                          );
            return $value;
        }
        $value = array('post' => $post);
        return $value;
        // return $attachment;
    }

    public function show(Request $request,$id)
    {
        $post = Post::find($id);
        return array('post' => $post);
    }

    public function mypost(Request $request,$id)
    {
        $post = Post::find($id)->where('user_id','=', $request->user()->id)->get();
        return array('post' => $post);
    }


    public function edit($id) //view на изменение объекта (web.php)
    {
        //(web.php)
    }

    public function update(StorePostRequest $request, $id)  //api запрос с edit
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
}
