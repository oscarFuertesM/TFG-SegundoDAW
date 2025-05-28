<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        Log::info('Intento de login', [
            'mailUsuario' => $request->mailUsuario,
            'passUsuario' => $request->passUsuario
        ]);
    
        $user = User::where('mailUsuario', $request->mailUsuario)->first();
    
        if (!$user) {
            Log::warning('Usuario no encontrado', ['mailUsuario' => $request->mailUsuario]);
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }
    
        if (!Hash::check($request->passUsuario, $user->passUsuario)) {
            Log::warning('Contraseña incorrecta', [
                'mailUsuario' => $request->mailUsuario,
                'input' => $request->passUsuario,
                'hash' => $user->passUsuario
            ]);
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }
    
        Log::info('Login correcto', ['mailUsuario' => $request->mailUsuario]);
    
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }
    

    public function logout(Request $request)
{
    // Revoca el token actual
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Sesión cerrada.']);
}


    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function register(Request $request)
{
    $request->validate([
        'mailUsuario' => 'required|email|unique:usuarios,mailUsuario',
        'passUsuario' => 'required|min:6',
        'nombreUsuario' => 'required',
        'permisos' => 'required|integer'
    ]);

    $user = User::create([
        'mailUsuario' => $request->mailUsuario,
        'passUsuario' => $request->passUsuario,
        'nombreUsuario' => $request->nombreUsuario,
        'permisos' => $request->permisos
    ]);

    return response()->json([
        'message' => 'Usuario registrado correctamente',
        'user' => $user
    ], 201);
}
}
