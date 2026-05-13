<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireSessionLogin
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('EmployeeID')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect('/login');
        }

        return $next($request);
    }
}

