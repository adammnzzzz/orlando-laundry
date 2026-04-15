<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$levels): Response
    {
        if (auth()->check() && auth()->user()->level) {
            $userLevelName = auth()->user()->level->level_name;
            if (in_array($userLevelName, $levels)) {
                return $next($request);
            }
        }
        abort(403, 'Unauthorized action.');
    }
}
