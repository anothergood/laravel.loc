<?php

namespace App\Http\Controllers;

use App\Like;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLikeRequest;

class LikeController extends Controller
{
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
    public function store(StoreLikeRequest $request)
    {
        $like = Like::where('user_id', '=', $request->user()->id)->where('post_id','=', $request->post_id);
        if ($like->exists()) {
            Like::destroy($like->first()->id);
            return $value = array('message' => 'like was cancelled',
                                  'post_id' => $request->post_id);
        } else {
            $like = new Like;
            $like->user_id = $request->user()->id;
            $like->post_id = $request->post_id;
            $like->save();

            $value = array('like' => $like,
                           'post_id' => $request->post_id);
            return $value;
        }
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

    }
}
