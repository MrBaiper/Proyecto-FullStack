<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\User;
use Illuminate\Support\Facades\hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //Registro
    public function register(Request $request){
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Usuario registrado', 'user' => $user], 201);
    }

    //Iniciar Sesion
    public function login(Request $request){

        $credentials = $request->only('email', 'password');

        if (!$token = Auth::attempt($credentials)){
            return response()->json(['error' => 'Credenciales invalidas'], 401);
        }

        return $this->respondWithToken($token);
    }

    //Usuario autenticado
    public function profile(){
        return response()->json(Auth::user());
    }

    //Cerrar sesion
    public function logout(){
        Auth::logout();
        return response()->json(['message' => 'Sesion cerrada']);
    }

    //Refrescar token
    public function refresh(){
        return $this->respondWithToken(Auth::refresh());
    }

    //Respuesta con token
    protected function respondWithToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }
}
