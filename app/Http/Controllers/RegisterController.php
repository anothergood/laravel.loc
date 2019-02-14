<?php

namespace App\Http\Controllers;

use App\User;
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
        return response(['user' => $user,
                           'accessToken' => $token]);
    }
}
