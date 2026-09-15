<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        app()->setLocale(session('locale', config('app.locale', 'id')));

        $request->attributes->set('csp_nonce', bin2hex(random_bytes(16)));

        return $next($request);
    }
}
