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
        if (! Auth::check()) {
            return redirect()->route('auth.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $allowed = explode('|', $roles);

        if (! in_array(Auth::user()->role, $allowed)) {
            abort(Response::HTTP_FORBIDDEN, 'Anda tidak memiliki akses.');
        }

        return $next($request);
    }

}
