<?php

namespace App\Http\Middleware;

use Closure;

class ParamsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $params= $request->toArray();
        if (!is_array($params)) {
            $params = [];
        }
        $request['params'] = $params;
        return $next($request);
    }
}
