<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Verificar se o email já existe
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'message' => 'Email already exists.',
            ], 409); // Código HTTP 409: Conflito
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'message' => 'User registered successfully.',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Registration error: ', ['exception' => $e]);

            return response()->json([
                'message' => 'Failed to register user. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function login(Request $request)
    {
        // Verificar se o cabeçalho Authorization contém um token
        if ($request->hasHeader('Authorization')) {
            $token = $request->bearerToken(); // Obtém o token do cabeçalho Authorization
    
            if ($token) {
                try {
                    // Verificar se o token é válido
                    $user = PersonalAccessToken::findToken($token)?->tokenable;
    
                    if ($user) {
                        return response()->json([
                            'message' => 'Login successful via token.',
                            'user' => [
                                'id' => $user->id,
                                'name' => $user->name,
                                'email' => $user->email,
                            ],
                        ]);
                    }
    
                    return response()->json([
                        'message' => 'Invalid token.',
                    ], 401);
                } catch (\Exception $e) {
                    return response()->json([
                        'message' => 'Error validating token.',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }
        }
    
        // Se o token não for fornecido, autenticar com e-mail e senha
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json([
                'message' => 'User with the provided email does not exist.',
            ], 404);
        }
    
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid password.',
            ], 401);
        }
    
        try {
            // Gerar o token após a autenticação bem-sucedida
            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'message' => 'Login successful via email and password.',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Login error: ', ['exception' => $e]);
    
            return response()->json([
                'message' => 'Failed to generate token. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    


    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'message' => 'Logout successful.',
            ]);
        } catch (\Exception $e) {
            Log::error('Logout error: ', ['exception' => $e]);

            return response()->json([
                'message' => 'Failed to logout. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $request->user()->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422); // Status HTTP 422 para erros de validação
        }

        try {
            $user = $request->user(); // Obtenha o usuário autenticado

            // Atualize os campos apenas se foram enviados
            if ($request->has('name')) {
                $user->name = $request->name;
            }
            if ($request->has('email') && $request->email !== $user->email) {
                $user->email = $request->email;
            }
            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }

            // Salve as alterações no banco de dados
            $user->save();

            return response()->json([
                'message' => 'Profile updated successfully.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Profile update error: ', ['exception' => $e]);

            return response()->json([
                'message' => 'Failed to update profile. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
