<?php

namespace App\Http\Controllers;

use App\User;
use App\VerifyUser;
use App\Mail\VerifyMail;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;

class RegisterController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        $user = new User;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password =  bcrypt($request ->password);
        $user->save();
        $token = $user->createToken('MyToken')->accessToken;

        $verifyUser = VerifyUser::create([
            'user_id' => $user->id,
            'token' => sha1(time())
        ]);
        \Mail::to($user->email)->send(new VerifyMail($user));

        return response(['user' => $user,
        'accessToken' => $token]);
    }

    public function verifyUser($token)
    {
        $verifyUser = VerifyUser::where('token', $token)->first();
        if (isset($verifyUser)) {
            $user = $verifyUser->user;
            if (!$user->verified) {
                $verifyUser->user->verified = 1;
                $verifyUser->user->save();
                return response(['message' => 'Your e-mail is verified. You can now login.']);
            } else {
                return response(['message' => 'Your e-mail is already verified. You can now login.'], 422);
            }
        } else {
            return response(['message' => 'Sorry your email cannot be identified.'], 422);
        }
    }
}
