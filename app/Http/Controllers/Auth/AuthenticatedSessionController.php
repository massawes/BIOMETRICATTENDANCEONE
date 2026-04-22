<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $role = strtolower(auth()->guard('web')->user()->role->name ?? '');

        return match ($role) {
            'student' => redirect()->route('studentdashboard'),
            'lecturer' => redirect()->route('lecturerdashboard'),
            'hod' => redirect()->route('hoddashboard'),
            'registrar' => redirect()->route('registrardashboard'),
            'examination_officer' => redirect()->route('examdashboard'),
            'quality_assurance' => redirect()->route('qadashboard'),
            'director_academic' => redirect()->route('directordashboard'),
            'rector' => redirect()->route('rectordashboard'),
            default => redirect()->route('home'),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
