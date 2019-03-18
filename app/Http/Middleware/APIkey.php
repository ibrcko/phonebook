<?php

namespace App\Http\Middleware;

use Closure;

class APIkey {

    public function handle($request, Closure $next)
    {
        if ($request->header(config('auth.apiAccess.apiKey')) != config('auth.apiAccess.apiSecret')){
            return response('Unauthorized.', 401);
        }

        if (!$request->expectsJson()) {
            return response('Invalid request.', 403);
        }
        return $next($request);
    }
}
