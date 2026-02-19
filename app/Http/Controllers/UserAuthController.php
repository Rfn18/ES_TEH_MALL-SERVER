<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator ;

class UserAuthController extends Controller
{
    public function register(Request  $request) {
        $validator = Validator::make($request->all(), [
            'username' => "required|string",
            'password' => 'required|confirmed|min:8',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        };

        $user = User::create([
            'username' => $request->username,
            'password' => $request->password
        ]);

       return response()->json([
        'message' => 'User Created ',
        ]);
    }

    public function login(Request $request) {
         $validator = Validator::make($request->all(), [
            'username' => "required|string",
            'password' => 'required|min:8',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        };

        $user = User::where('username', $request->username)->first();
        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'invalid credentials'
            ], 401);
        }

        $token = $user->createToken($user->username.'-AuthToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
        ]);
    }

    public function logout() {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged Out'
        ]);
    }
} 
