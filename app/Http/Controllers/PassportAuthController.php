<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class PassportAuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => "required|min:3",
            'email' => "required|email|unique:users",
            'password' => "required|min:6",
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('authToken')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if(auth()->attempt($credentials)){
            $token = auth()->user()->createToken('authToken')->accessToken;
            return response()->json(['token' => $token], 200);
        }else{
            return response()->json(['error' => 'UnAuthorized'], 401);
        }
    }

    public function details(){
        return response()->json(['user' => auth()->user()], 200);
    }

}
