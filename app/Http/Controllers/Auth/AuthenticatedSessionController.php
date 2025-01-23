<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    use ApiResponses;
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();
        $user = $request->user();

    /**
     * Для авторизации через bearer-токен
     */
        // $user->tokens()->where('name', 'auth-token')->delete();
        // $token = $user->createToken('auth-token');

        // return response()->json([
        //     'user' => $user,
        //     'token' => $token->plainTextToken,
        // ]);
        
        return $this->success('User logged in successfully', ['user'=>$user]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        //$request->session()->regenerateToken();

        return response()->noContent();
    }
}
