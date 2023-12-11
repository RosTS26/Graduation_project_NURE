<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectOnUnsupportedMethods
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
