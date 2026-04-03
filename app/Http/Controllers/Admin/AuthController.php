<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Show the login page.
     * GET /admin/login
     * (guest middleware handles redirect if already logged in)
     */
    public function showLogin()
    {
        return view('login');
    }

    /**
     * Handle login form submission.
     * POST /admin/login
     * Validation is handled by LoginRequest before this method runs.
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            $request->session()->regenerate();

            $user = Auth::user();

            // Check if user has admin role
            if (! $user->isAdmin()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'You do not have admin access.',
                ])->onlyInput('email');
            }

            // Check if account is active
            if (! $user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been deactivated. Contact support.',
                ])->onlyInput('email');
            }

            // Update last login info
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            Log::info('Admin logged in', [
                'admin_id' => $user->id,
                'email'    => $user->email,
                'ip'       => $request->ip(),
            ]);

            // Redirect to intended page or dashboard
            return redirect()->intended(route('admin.dashboard'));
        }

        // Credentials did not match
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout.
     * POST /admin/logout
     */
    public function logout(Request $request)
    {
        Log::info('Admin logged out', ['admin_id' => Auth::id()]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login.page')
            ->with('success', 'You have been logged out successfully.');
    }
}