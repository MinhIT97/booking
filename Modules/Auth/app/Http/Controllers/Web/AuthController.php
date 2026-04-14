<?php

namespace Modules\Auth\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
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
     * GET /login
     */
    public function showLoginForm(): View
    {
        return view('auth::auth.login');
    }

    /**
     * POST /login
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        try {
            $this->authService->login(LoginDTO::fromRequest($request->validated()), true);
            
            return redirect()->intended(route('host.dashboard'))
                ->with('success', 'Welcome back!');
        } catch (Exception $e) {
            return back()->withErrors(['email' => $e->getMessage()])->withInput();
        }
    }

    /**
     * GET /register
     */
    public function showRegisterForm(): View
    {
        return view('auth::auth.register');
    }

    /**
     * POST /register
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        try {
            $user = $this->authService->register(RegisterDTO::fromRequest($request->validated()));
            
            // Auto login after registration
            $this->authService->login(new LoginDTO($user->email, $request->password), true);

            return redirect()->route('host.dashboard')
                ->with('success', 'Account created successfully!');
        } catch (Exception $e) {
            return back()->withErrors(['email' => $e->getMessage()])->withInput();
        }
    }

    /**
     * POST /logout
     */
    public function logout(): RedirectResponse
    {
        $this->authService->logoutWeb();

        return redirect('/')->with('success', 'Logged out successfully');
    }
}
