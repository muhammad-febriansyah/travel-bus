<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class HandleAppearance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isFilamentRequest = $request->routeIs('filament.*') || str_starts_with($request->path(), 'admin');

        $appearance = $isFilamentRequest
            ? $request->cookie('appearance') ?? 'system'
            : 'light';

        View::share('appearance', $appearance);

        return $next($request);
    }
}
