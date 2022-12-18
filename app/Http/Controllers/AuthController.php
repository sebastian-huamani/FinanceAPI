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
                'res' => false,
                'msg' => 'Credenciales Invalidas',
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'res' => true,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);

    }

    public function infoUser(Request $request )
    {
        return response()->json(
            $request->user()
        );
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        
        return response()->json([
            'res' => true,
            'msg' => 'Te has Deslogeado'
        ]);
    }

    public function pruebas(Request $request)
    {
        try {
            $body = [];
            for ($i=0; $i < sizeof($request->body) ; $i++) { 
                array_push($body ,[$request->template[$i], $request->body[$i]] );
            }
            return response()->json([
                'res' => $request->all(),
                'get' => $body,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'e' => $e->getMessage()
            ]);
        }
    }

    
}
