<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $token = $request->user()->createToken('authToken')->plainTextToken;

            return new JsonResponse(
                data: ['success' => true, 'token' => $token],
                status: JsonResponse::HTTP_OK,
            );
        }

        return new JsonResponse(
            data: ['message' => 'login failed.'],
            status: JsonResponse::HTTP_UNAUTHORIZED,
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return new JsonResponse(
            data: ['message' => 'logout success.'],
            status: JsonResponse::HTTP_OK,
        );
    }
}
