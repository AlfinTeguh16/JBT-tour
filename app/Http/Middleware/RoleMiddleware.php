<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
// use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $roles  Pipe-separated roles, contoh: "admin|staff"
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $roles)
    {
        // Jika belum terautentikasi -> redirect ke halaman login (atau JSON 401 untuk AJAX)
        if (! Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Silakan login terlebih dahulu.'], Response::HTTP_UNAUTHORIZED);
            }

            // redirect()->guest() akan menyimpan intended URL sehingga redirect()->intended() bekerja
            return redirect()->guest(route('auth.login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $allowed = array_filter(explode('|', $roles));

        $userRole = Auth::user()->role ?? null;

        // Jika role pengguna tidak ada atau tidak diizinkan -> 403 (atau JSON 403 untuk AJAX)
        if (! $userRole || ! in_array($userRole, $allowed, true)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Anda tidak memiliki akses.'], Response::HTTP_FORBIDDEN);
            }

            abort(Response::HTTP_FORBIDDEN, 'Anda tidak memiliki akses.');
        }

        return $next($request);
    }
}
