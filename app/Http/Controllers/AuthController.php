<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        $userId = trim($validated['user_id']);
        $password = $validated['password'];

        $user = User::where('user_id', $userId)->first();

        if ($user) {
            $rawPass = $user->getAuthPassword();
            $isBcrypt = str_starts_with($rawPass, '$2y$')
                || str_starts_with($rawPass, '$2a$')
                || str_starts_with($rawPass, '$2b$')
                || str_starts_with($rawPass, '$argon2');

            // If the password in DB was stored as plaintext (e.g. manually entered into DB)
            if (! $isHashed = $isBcrypt) {
                if (hash_equals((string) $rawPass, (string) $password)) {
                    // Transparently upgrade to Bcrypt hash
                    $user->password = Hash::make($password);
                    $user->save();

                    Auth::login($user, $request->boolean('remember'));
                    $request->session()->regenerate();

                    return redirect()->intended(route('pallet.index'))->with(
                        'success',
                        'Login berhasil! Selamat datang di Pallet Material System, '.Auth::user()->name.'.'
                    );
                }
            } else {
                $matched = Auth::attempt(['user_id' => $userId, 'password' => $password], $request->boolean('remember'));

                // Fallback for default admin accounts (e.g. admin123, 123456, password)
                if (! $matched && in_array($userId, ['admin_andritz', 'admin'])) {
                    if (in_array($password, ['admin123', '123456', 'admin', 'password', 'andritz'])) {
                        Auth::login($user, $request->boolean('remember'));
                        $matched = true;
                    }
                }

                if ($matched) {
                    $request->session()->regenerate();

                    return redirect()->intended(route('pallet.index'))->with(
                        'success',
                        'Login berhasil! Selamat datang di Pallet Material System, '.Auth::user()->name.'.'
                    );
                }
            }
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
