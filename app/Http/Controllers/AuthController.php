<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('pallet.index');
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'user_id.required' => 'User ID wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = [
            'user_id' => trim($validated['user_id']),
            'password' => $validated['password'],
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('pallet.index'))->with(
                'success',
                'Login berhasil! Selamat datang di Pallet Material System, '.Auth::user()->name.'.'
            );
        }

        return back()
            ->withInput($request->only('user_id', 'remember'))
            ->withErrors([
                'user_id' => 'User ID atau Password yang Anda masukkan tidak terdaftar / tidak sesuai.',
            ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah berhasil keluar dari sistem.');
    }
}
