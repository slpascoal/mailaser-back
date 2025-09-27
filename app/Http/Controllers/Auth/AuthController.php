<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    /**
     * Registra um novo usuário e retorna um token de API.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $token = $this->authService->registerService($validatedData);

        return response()->json([
            'message' => 'Usuário registrado com sucesso!',
            'token' => $token,
        ], 201);
    }

    /**
     * Autentica um usuário e retorna um token de API.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $loginResponse = $this->authService->loginService($credentials);

        return response()->json([
            'message' => $loginResponse['message'],
            'token' => $loginResponse['token']
        ], $loginResponse['status']);
    }

    /**
     * Faz logout do usuário, invalidando o token atual.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso!']);
    }

    /**
     * Deleta a conta do usuário autenticado.
     */
    public function destroy(Request $request): JsonResponse
    {
        $user = $request->user();

        $user->tokens()->delete();

        $user->delete();

        return response()->json(['message' => 'Sua conta foi deletada com sucesso.']);
    }
}
