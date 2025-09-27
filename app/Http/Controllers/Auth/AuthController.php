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

        $registerResponse = $this->authService->registerService($validatedData);

        return response()->json([
            'data' => $registerResponse['data'],
        ], $registerResponse['status']);
    }

    /**
     * Autentica um usuário e retorna um token de API.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $loginResponse = $this->authService->loginService($credentials);

        return response()->json([
            'data' => $loginResponse['data'],
        ], $loginResponse['status']);
    }

    /**
     * Faz logout do usuário, invalidando o token atual.
     */
    public function logout(Request $request): JsonResponse
    {
        $logoutResponse = $this->authService->logoutService($request->user());

        return response()->json([
            'data' => $logoutResponse['data'],
        ], $logoutResponse['status']);
    }

    /**
     * Deleta a conta do usuário autenticado.
     */
    public function destroy(Request $request): JsonResponse
    {
        $deleteUserResponse = $this->authService->deleteUserService($request->user());

        return response()->json([
            'data' => $deleteUserResponse['data'],
        ], $deleteUserResponse['status']);
    }

    /**
     * Retorna os dados do usuário autenticado.
     */
    public function showUser(Request $request): JsonResponse
    {
        $showUserData = $this->authService->showUserService($request->user());

        return response()->json([
            'data' => $showUserData['data'],
        ], $showUserData['status']);
    }
}
