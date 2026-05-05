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
            if (in_array($user->level, [1, 2])) {
                return $next($request);
            }
        }

        return redirect()->route('getlogin')->with(['flag' => 'danger', 'message' => 'Vui lòng đăng nhập với tài khoản quản trị']);
    }
}
