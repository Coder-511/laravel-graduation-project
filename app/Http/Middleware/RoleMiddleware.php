<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware {
    public function handle(Request $request, Closure $next, $role) {
        if (!Auth::check() || Auth::user()->user_type !== $role) {
            abort(403, 'Unauthorized access'); // or redirect('/login')
        }

        return $next($request);
    }
}
