<?php

namespace Modules\Auth\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Auth\DTOs\LoginDTO;
use Modules\Auth\DTOs\RegisterDTO;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Auth\Services\AuthService;
use Exception;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * POST /api/auth/register
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->register(RegisterDTO::fromRequest($request->validated()));
            $token = $this->authService->generateToken($user);

            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user,
                'token' => $token
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * POST /api/auth/login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->login(LoginDTO::fromRequest($request->validated()));
            $token = $this->authService->generateToken($user, $request->input('device_name', 'web_app'));

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    /**
     * POST /api/auth/logout
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logoutApi($request->user());

        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * GET /api/auth/me
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $request->user()->load('role')
        ]);
    }
}
