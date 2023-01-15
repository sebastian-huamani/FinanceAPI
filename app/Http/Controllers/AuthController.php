<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\DataInfoUser;
use App\Models\User;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(AuthRequest $request)
    {
        try{
            $user = User::create([   
                'name' => $request->name,
                'email' => $request->email,
                'lastname' => $request->lastname,
                'password' => Hash::make($request->password)
            ]);
    
            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                // "request" => $request->all(),
                // "user" => $user,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]);

        } catch(\Exception $e) {
            return response()->json([
                'E' => $e->getMessage()
            ]);
        }
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

    public function updateInfoUser(Request $request)
    {
        try {
            $user = User::where('id', auth()->user()->id)->first();
    
            $user->update([
                'name' => $request->name,
                'lastname' => $request->lastname,
            ]);
    
            return response()->json([
                'res' => true,
                'msg' => 'Actualizado Tu Perfil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => true,
                'msg' => 'Perfil No Actualizado',
                'e' => $e->getMessage()
            ]);
        }
    }



    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        
        return response()->json([
            'res' => true,
            'msg' => 'Te has Deslogeado'
        ]);
    }

    public function pruebas()
    {
        try {
            $user = User::where('id', 4)->first();
            
            $dateNow = Carbon::now(new DateTimeZone('America/Lima'));
            $lastDataMonth = $user->data_info_user()->whereDate('data_info_users.created_at', '<', $dateNow)->first();

            return response()->json([
                'res' => true,
                'msg' => $lastDataMonth
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'e' => $e->getMessage()
            ]);
        }
    }

    
}
