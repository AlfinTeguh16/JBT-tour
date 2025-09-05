<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'name'    => ['required', 'string'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();
                Log::info('Login success', ['user_id' => Auth::id(), 'name' => $request->name]);
                return redirect()->intended(route('dashboard'));
            }

            Log::warning('Login failed: invalid credentials', ['name' => $request->name]);
            return back()->withErrors(['name' => 'Nama atau password salah.'])->onlyInput('name');

        } catch (\Throwable $e) {
            Log::error('Login error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['name' => 'Terjadi kesalahan pada server.'])->onlyInput('name');
        }
    }

    public function logout(Request $request)
    {
        try {
            $id = Auth::id();
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            Log::info('Logout success', ['user_id' => $id]);

            return redirect()->route('auth.login');
        } catch (\Throwable $e) {
            Log::error('Logout error', ['error' => $e->getMessage()]);
            return redirect()->route('auth.login');
        }
    }
}
