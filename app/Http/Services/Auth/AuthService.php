<?php

namespace App\Http\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function registerService($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return $user->createToken('api-token')->plainTextToken;
    }

    public function loginService($data): array
    {
        if (!Auth::attempt($data)) {
            return ['message' => 'Credenciais inválidas.', 'token' => null, 'status' => 401];
        }

        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        return ['message' => 'Login realizado com sucesso!', 'token' => $token, 'status' => 200];
    }
}
