<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class APIkey
 * @package App\Http\Middleware
 * Custom Middleware that assures API endpoint security
 */
class APIkey {

    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     * Method that checks if the request headers are correct
     */
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
