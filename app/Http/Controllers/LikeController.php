<?php

namespace App\Http\Controllers;

use App\User;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLikeRequest;

class LikeController extends Controller
{
    public function store(StoreLikeRequest $request)
    {
        $user = $request->user();
        $like = $user->likes()->where('post_id','=',$request->post_id);
        if($like->exists()){
            $user->likes()->detach($request->post_id);
            return response(['message' => 'like is canceled']);
        } else {
            $user->likes()->attach($request->post_id);
            return response($like->get());
        }
    }
}
