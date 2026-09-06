<?php

namespace App\Http\Middleware;

use Closure;
use Database\Seeders\DemoAccountSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureNotDemo
{
    public function handle(Request $request, Closure $next)
    {
        if (! in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return $next($request);
        }

        if (Auth::check() && Auth::user()->email === DemoAccountSeeder::DEMO_EMAIL) {
            return back()->with('error', __('messages.demo.action_blocked'));
        }

        return $next($request);
    }
}