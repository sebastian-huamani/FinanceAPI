<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(AuthRequest $request)
    {
        $user = User::create([   
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            // "request" => $request->all(),
            // "user" => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function login(Request $request)
    {

        if( !Auth::attempt($request->only('email', 'password')) ){
            return response()->json([
                'msg' => 'Invalid login Credentials'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function infoUser(Request $request )
    {
        return $request->user();
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        // $user->tokens()->delete();
        
        return response()->json([
            'msg' => 'Te has Deslogeado'
        ]);
    }

    
}
