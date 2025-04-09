<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    /**
     * Menangani autentikasi login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'password' => 'required|string',
        ]);

        // Cari user berdasarkan nama
        $user = User::where('name', $request->name)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()->withErrors(['name' => 'Nama atau password salah'])->withInput();
        }

        Auth::login($user, $request->boolean('remember'));

        return match($user->role) {
            'direktur' => redirect()->route('dashboard.direktur'),
            'admin'    => redirect()->route('dashboard.admin'),
            'akuntan'  => redirect()->route('dashboard.akuntan'),
            'pengawas' => redirect()->route('dashboard.pengawas'),
            default    => redirect()->route('auth.login')->with('error', 'Role tidak dikenali'),
        };
    }

    /**
     * Menangani proses logout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }
}