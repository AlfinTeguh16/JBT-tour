<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $roles)
    {
        // Cek apakah user terautentikasi
        if (! Auth::check()) {
            // Mengarahkan ke halaman login dengan pesan error
            return redirect()->route('auth.login')->with('error', 'Session Anda telah berakhir. Silakan login kembali.');
        }

        // Mengizinkan beberapa role (misalnya admin|staff)
        $allowed = explode('|', $roles);

        // Cek apakah role user sesuai dengan yang diizinkan
        if (! in_array(Auth::user()->role, $allowed)) {
            abort(Response::HTTP_FORBIDDEN, 'Anda tidak memiliki akses.');
        }
        // if (!Auth::check()) {
        //     abort(Response::HTTP_NOT_FOUND); // 404 Error
        // }

        // Lanjutkan ke proses berikutnya
        return $next($request);
    }

}
