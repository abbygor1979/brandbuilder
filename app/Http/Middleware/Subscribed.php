<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Subscribed
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->subscription || auth()->user()->subscription->status !== 'active') {
            return redirect()->route('subscriptions.index')->with('error', 'You need an active subscription to access this feature.');
        }

        return $next($request);
    }
}