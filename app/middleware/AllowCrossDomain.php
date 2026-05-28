<?php
namespace app\middleware;

use app\Request;
use Closure;

class AllowCrossDomain
{
    public function handle(Request $request, Closure $next)
    {
        // // // 允许所有域名访问
        // // header('Access-Control-Allow-Origin: *');
        // // header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');
        // // header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, Authorization, X-Requested-With');
        // // return $next($request);

        // $response = $next($request);

        // $response->header([
        //     'Access-Control-Allow-Origin' => '*',
        //     'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS, PATCH',
        //     'Access-Control-Allow-Headers' => 'Origin, Accept, Content-Type, Authorization, X-Requested-With',
        // ]);

        // if (strtoupper($request->method()) == "OPTIONS") {
        //     return $response;
        // }

        // return $response;

        // 处理预检请求 (OPTIONS 请求)
        // 对于预检请求，直接返回 204 状态码，不必执行后续的控制器逻辑
        if ($request->isOptions()) {
            return response('', 204)
                ->header([
                    'Access-Control-Allow-Origin'      => $request->header('origin', '*'),
                    'Access-Control-Allow-Methods'     => 'GET, POST, PUT, DELETE, OPTIONS',
                    'Access-Control-Allow-Headers'     => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With, X-Token',
                    'Access-Control-Allow-Credentials' => 'true',
                    'Access-Control-Max-Age'           => 86400,
                ]);
        }

        // 处理正常的业务请求，并在返回的响应中添加跨域头
        $response = $next($request);

        return $response->header([
            'Access-Control-Allow-Origin'      => $request->header('origin', 'http://localhost:8080'), // 注意：如果前端需要携带 Cookie，这里不能是 '*'，必须是具体的 origin
            'Access-Control-Allow-Credentials' => 'true',                                              // 是否允许携带 Cookie
            'Access-Control-Allow-Methods'     => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers'     => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With, X-Token',
        ]);
    }
}
