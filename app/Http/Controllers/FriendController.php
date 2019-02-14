<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFriendRequest;

class FriendController extends Controller
{
    public function inviteFriend(StoreFriendRequest $request)
    {
        $initiator = $request->user();
        $friend = $initiator->users()->where('user_id', '=', $request->user_id);
        if ($friend->exists()) {
            $status = $friend->first()->pivot->status;
            if ($status == 'pending') {
                return response(['message'=>'request already exists'], 422);    //422 Unprocessable Entity??
            } elseif ($status == 'approved') {
                return response(['message'=>'user is already a friend'], 422);   //422 Unprocessable Entity??
            } elseif ($status == 'denied') {
                return response(['message'=>'request denied'], 422);           //422 Unprocessable Entity??
            }
        } else {
            $initiator->users()->attach($request->user_id, ['status' => 'pending']);
            return response(['message' => 'invitation sent']);
        }
    }

    public function approveFriend(StoreFriendRequest $request)
    {
        $initiator = User::find($request->user_id);
        $friend = $initiator->users()->where('user_id', '=', $request->user()->id);
        if ($friend->exists()) {
            $status = $friend->first()->pivot->status;
            if ($status == 'pending') {
                $initiator->users()->updateExistingPivot($request->user()->id, ['status' => 'approved']);
                return response(['message' => 'invitation approved']);
            } elseif ($status == 'approved') {
                return response(['message'=>'user is already a friend'], 422);   //422 Unprocessable Entity??
            } elseif ($status == 'denied') {
                return response(['message'=>'request denied'], 422);   //422 Unprocessable Entity??
            }
        } else {
            return response(['message'=>'there is no request from this user'], 422);   //422 Unprocessable Entity??
        }
    }
}
