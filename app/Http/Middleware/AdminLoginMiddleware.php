<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->level === 1 || $user->level === 2) {
                return $next($request);
            }
        }

        return redirect()->route('admin.getLogin');
    }
}
