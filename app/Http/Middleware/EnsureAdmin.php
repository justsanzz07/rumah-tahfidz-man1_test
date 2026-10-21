<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check() || ! Auth::user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Tindakan ini memerlukan hak akses Administrator.',
                ], 403);
            }

            return redirect()->route('login')
                ->withErrors(['auth' => 'Silakan masuk sebagai Administrator terlebih dahulu untuk mengakses menu ini.']);
        }

        return $next($request);
    }
}
