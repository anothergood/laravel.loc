<?php

namespace App\Http\Controllers;

use App\UserUser;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFriendRequest;

class FriendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function invitefriend(StoreFriendRequest $request)
     {
           $friend = UserUser::where('user_initiator_id', '=', $request->user()->id)->where('user_id','=', $request->user_id);
           if ($friend->exists()) {
               $pivot = $friend->get();
               if ($pivot->contains('status', 'pending')) {
                   return $value = array('message'=>'request already exists');
               } elseif ($pivot->contains('status', 'approved')) {
                   return $value = array('message'=>'user is already a friend');
               } elseif ($pivot->contains('status', 'denied')) {
                   return $value = array('message'=>'request denied');
               }
           } else {
               $initiator = User::find($request->user()->id);
               $user = User::find($request->user_id);
               $initiator->users()->save($user, ['status' => 'pending']);
               return $user;
           }


         // $friend = Friend::where('user_initiator_id', '=', $request->user()->id)->where('user_id','=', $request->user_id);
         // if ($friend->exists()) {
         //     if ($friend->status = 'pending') {
         //         return $value = array('message'=>'request already exists');
         //     } elseif ($friend->status = 'approved') {
         //         return $value = array('message'=>'user is already a friend');
         //     } elseif ($friend->status = 'denied') {
         //         return $value = array('message'=>'request denied');
         //     }
         // } else {
         //     $friend = new Friend;
         //     $friend->user_initiator_id = $request->user()->id;
         //     $friend->user_id = $request->user_id;
         //     $friend->status = 'pending';
         //     $friend->save();
         //     return $friend;
         // }
     }

     public function approvefriend(StoreFriendRequest $request)
     {
       $friend = UserUser::where('user_initiator_id','=', $request->user_id)->where('user_id', '=', $request->user()->id);
         if ($friend->exists()) {
             $pivot = $friend->get();
             if ($pivot->contains('status','pending')) {
               $initiator = User::find($request->user()->id);
               $user = User::find($request->user_id);
               $user->users()->updateExistingPivot($initiator, ['status' => 'approved']);
               return $user;
             } elseif ($pivot->contains('status','approved')) {
                 return $value = array('message'=>'user is already a friend');
             } elseif ($pivot->contains('status','denied')) {
                 return $value = array('message'=>'request denied');
             }
         } else {
             return $value = array('message'=>'there is no request from this user');
         }


         // if (Friend::where('user_initiator_id','=', $id)->where('user_id', '=', $request->user()->id)->exists()) {
         //     $friend = Friend::where('user_initiator_id','=', $id)->where('user_id', '=', $request->user()->id)->first();
         //     if ($friend->status = 'pending') {
         //         $friend->status = $request->status;
         //         $friend->save();
         //         return $friend;
         //     } elseif ($friend->status = 'approved') {
         //         return $value = array('message'=>'user is already a friend');
         //     } elseif ($friend->status = 'denied') {
         //         return $value = array('message'=>'request denied');
         //     }
         // } else {
         //     return $value = array('message'=>'there is no request from this user');
         // }
     }


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

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      //
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
