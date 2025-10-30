<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator as Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    //Registro
    public function register(Request $request){
        
        $data = Auth::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ], [
            'name.required' => 'El nombre es obligatorio',
            'email.required' => 'El correo es obligatorio',
            'email.email' => 'El correo no es valido',
            'email.unique' => 'El correo ya esta registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
        ]);

        if ($data->fails()) {
            return response()->json(['errors' => $data->errors()], Response::HTTP_BAD_REQUEST);
        }

        $existingUser = User::where('email',htmlspecialchars($request->input('email')))->first();
        if (!$existingUser){
            $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            ]);

            if (!$user) {
                return response()->json(['error' => 'Error al crear el usuario'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json($user, Response::HTTP_CREATED);
        }else{
            return response()->json(['error' => 'El correo ya esta registrado'], Response::HTTP_BAD_REQUEST);
        }

        //$token = JWTAuth::fromUser($user);

        //return response()->json(['message' => 'Usuario registrado', 'user' => $user,'token' => $token], 201);
    }

    //Iniciar Sesion
    public function login(Request $request){
        

        $val = Auth::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'El correo es obligatorio',
            'email.email' => 'El correo no es valido',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
        ]);

        if ($val->fails()) {
            return response()->json(['errors' => $val->errors()], Response::HTTP_BAD_REQUEST);
        }

        $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)){
            return response()->json(['error' => 'Credenciales invalidas'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($token);
    }

    //Usuario autenticado
    public function profile(){
        return response()->json(auth()->user());
    }

    //Cerrar sesion
    public function logout(){
        auth()->logout();

        try {
            $token = JWTAuth::getToken();
            if (!$token){
                return response()->json(['error' => 'Token no encontrado'], Response::HTTP_BAD_REQUEST);
            }

            JWTAuth::invalidate($token);
            
            return response()->json(['message' => 'Sesion cerrada'], Response::HTTP_OK);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token invalido'], Response::HTTP_UNAUTHORIZED);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Error al cerrar sesion'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //Refrescar token
    public function refresh(){
        try {
            $token = JWTAuth::getToken();
            if (!$token){
                return response()->json(['error' => 'Token no encontrado'], Response::HTTP_BAD_REQUEST);
            }
            $nuevo_token = JWTAuth::refresh($token);
            JWTAuth::invalidate($token);
            return $this->respondWithToken($nuevo_token);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token invalido'], Response::HTTP_UNAUTHORIZED);
        } catch (JWTException $e) {
            return response()->json(['error' => 'No se pudo cerrar la sesion'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //Respuesta con token
    protected function respondWithToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ],Response::HTTP_OK);
    }
}
