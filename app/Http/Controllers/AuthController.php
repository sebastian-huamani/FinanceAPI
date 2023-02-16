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
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'lastname' => $request->lastname,
                'password' => Hash::make($request->password)
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'res' => true,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'e' => $e->getMessage()
            ], 200);
        }
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'res' => false,
                'msg' => 'Correo o Contraseña Invalidas',
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        if ($user->email_verified_at == null) {
            return response()->json([
                'res' => false,
                'msg' => 'Esta cuenta aun no esta verificada'
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'res' => true,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function infoUser(Request $request)
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
            return response()->json([
                'res' => true,
                'msg' => User::get()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'e' => $e->getMessage()
            ]);
        }
    }

    public function unauthorized(Request $request)
    {
        return response()->json([
            "Mensaje" => "No autorizado, No se proporcionó Token o es invalido"
        ], 401);
    }
}
