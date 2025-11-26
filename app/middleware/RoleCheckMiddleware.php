<?php
namespace app\middleware;

use think\Middleware;
use think\Request;

class RoleCheckMiddleware extends Middleware
{
    public function handle(Request $request, \Closure $next)
    {
        return $next($request);
    }
}
