<?php

namespace app\middleware;

use app\Request;
use Closure;

class AllowCrossDomain
{
    public function handle(Request $request, Closure $next)
    {
        // // 允许所有域名访问
        // header('Access-Control-Allow-Origin: *');
        // header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');
        // header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, Authorization, X-Requested-With');
        // return $next($request);

        $response = $next($request);

        $response->header([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS, PATCH',
            'Access-Control-Allow-Headers' => 'Origin, Accept, Content-Type, Authorization, X-Requested-With',
        ]);

        if (strtoupper($request->method()) == "OPTIONS") {
            return $response;
        }

        return $response;
    }
}
