<?php

namespace App\Http\Middleware;

use Closure;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($req, Closure $next)
    {
        $user = $req->user;
        echo gettype($req);
        switch (gettype($req)) {
            default:
                break;
        }
        return $next($req);
    }
}
