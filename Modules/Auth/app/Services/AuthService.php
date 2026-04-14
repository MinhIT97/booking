<?php

namespace Modules\Auth\Services;

use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\DTOs\LoginDTO;
use Modules\Auth\DTOs\RegisterDTO;
use Modules\Auth\Repositories\UserRepositoryInterface;

class AuthService extends BaseService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    /**
     * Register a new user.
     */
    public function register(RegisterDTO $dto)
    {
        return $this->executeInTransaction(function () use ($dto) {
            $userRole = \App\Models\Role::where('name', 'user')->first();
            
            return $this->userRepository->create([
                'name' => $dto->name,
                'email' => $dto->email,
                'password' => Hash::make($dto->password),
                'role_id' => $userRole ? $userRole->id : null,
            ]);
        });
    }

    /**
     * Authenticate a user and return user info.
     * For Web, it also handles the session login.
     */
    public function login(LoginDTO $dto, bool $isWeb = false)
    {
        $user = $this->userRepository->findByEmail($dto->email);

        if (!$user || !Hash::check($dto->password, $user->password)) {
            throw new Exception('Invalid credentials provided.');
        }

        if ($isWeb) {
            Auth::login($user, $dto->remember);
        }

        return $user;
    }

    /**
     * Generate Sanctum token for API.
     */
    public function generateToken($user, string $deviceName = 'default')
    {
        return $user->createToken($deviceName)->plainTextToken;
    }

    /**
     * Logout for Web.
     */
    public function logoutWeb()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    /**
     * Logout for API (revoke tokens).
     */
    public function logoutApi($user)
    {
        $user->tokens()->delete();
    }
}
