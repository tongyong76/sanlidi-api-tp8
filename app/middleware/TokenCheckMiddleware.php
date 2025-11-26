<?php
namespace app\middleware;

use app\exception\FailedException;
use app\utils\Code;
use thans\jwt\exception\TokenBlacklistException;
use thans\jwt\exception\TokenExpiredException;
use thans\jwt\exception\TokenInvalidException;
use thans\jwt\facade\JWTAuth;
use think\Middleware;
use think\Request;

class TokenCheckMiddleware extends Middleware
{
    public function handle(Request $request, \Closure $next)
    {
        $authInfo = null;

        try {
            $authInfo = JWTAuth::auth();
            // // 将用户信息设置到请求中，以便后续使用
            // $auth = new CatchAuth();
            // $user = $auth->user();
            // if (!$user) {
            //     throw new FailedException('登录用户不合法', Code::LOST_LOGIN);
            // }

        } catch (\Exception $e) {
            if ($e instanceof TokenExpiredException) {
                throw new TokenExpiredException('token 过期', Code::LOGIN_EXPIRED);
            }
            if ($e instanceof TokenBlacklistException) {
                throw new TokenBlacklistException('token 被加入黑名单', Code::LOGIN_BLACKLIST);
            }
            if ($e instanceof TokenInvalidException) {
                throw new TokenInvalidException('token 不合法', Code::LOST_LOGIN);
            }

            throw new FailedException('登录用户不合法', Code::LOST_LOGIN);
        }

        if (!is_null($authInfo)) {
            $newAuthInfo = array(
                'uid' => $authInfo['uid'],
                'gid' => $authInfo['gid'],
            );
            Session('authInfo', $newAuthInfo);

            //$request->uid($authInfo('id'));
            //$request->gid($authInfo('group_id'));

            // $request('uid', 1);
            // $request = Request::instance();
            // $request->uid(999);
        }

        return $next($request);
    }
}
