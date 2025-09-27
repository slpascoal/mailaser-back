<?php

namespace App\Http\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function registerService($validatedData): array
    {
        try {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            $token = $user->createToken('api-token')->plainTextToken;

            $data = [
                'message' => 'Usuário registrado com sucesso!',
                'token' => $token
            ];

            $status =  201;
        } catch (\Exception $exception) {
            $data = [
                'message' => 'Erro ao registrar usuário.',
                'error' => $exception->getMessage()
            ];

            $status =  $exception->getCode();
        }

        return [
            'data' => $data,
            'status' => $status
        ];
    }

    public function loginService($credentials): array
    {
        try {
            if (!Auth::attempt($credentials)) {
                return ['message' => 'Credenciais inválidas.', 'token' => null, 'status' => 401];
            }

            $user = Auth::user();
            $token = $user->createToken('api-token')->plainTextToken;

            $data = [
                'message' => 'Login realizado com sucesso!',
                'token' => $token
            ];

            $status = 200;
        } catch (\Exception $exception) {
            $data = [
                'message' => 'Erro ao registrar usuário.',
                'error' => $exception->getMessage()
            ];

            $status =  $exception->getCode();
        }

        return [
            'data' => $data,
            'status' => $status
        ];
    }

    public function logoutService(mixed $user): array
    {
        try {
            $user->currentAccessToken()->delete();

            $data = [
                'message' => 'Logout realizado com sucesso!'
            ];

            $status = 200;
        } catch (\Exception $exception) {
            $data = [
                'message' => 'Erro ao fazer logout.',
                'error' => $exception->getMessage()
            ];

            $status =  $exception->getCode();
        }

        return [
            'data' => $data,
            'status' => $status
        ];
    }

    public function deleteUserService(mixed $user): array
    {
        try {
            $user->tokens()->delete();

            $user->delete();

            $data = [
                'message' => 'Usuário deletado com sucesso!'
            ];

            $status = 200;
        } catch (\Exception $exception) {
            $data = [
                'message' => 'Erro ao deletar usuário.',
                'error' => $exception->getMessage()
            ];

            $status =  $exception->getCode();
        }

        return [
            'data' => $data,
            'status' => $status
        ];
    }

    public function showUserService(mixed $user): array
    {
        try {
            $user = $user->toArray();

            $data = [
                'name' => $user['name'],
                'email' => $user['email']
            ];

            $status = 200;
        } catch (\Exception $exception) {
            $data = [
                'message' => 'Erro ao buscar dados do usuário.',
                'error' => $exception->getMessage()
            ];

            $status =  $exception->getCode();
        }

        return [
            'data' => $data,
            'status' => $status
        ];
    }
}
