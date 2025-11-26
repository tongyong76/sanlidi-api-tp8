<?php
declare (strict_types = 1);

namespace app\controller\u;

use app\model\User as UserModel;
use thans\jwt\facade\JWTAuth;
use think\facade\Request;

/**
 * 登录相关
 */
class Login extends BaseController
{
    /**
     * 小程序登录-用户名密码
     * POST https://gsqapi.sanlidi.com/u/login/wxapp
     */
    public function wxappLogin()
    {
        $post = Request::post();

        // 校验参数
        $username = $post['username'] ? $post['username'] : '';
        $password = $post['password'] ? $post['password'] : '';
        if ($username == '' || $password == '') {
            return $this->error('USER_LOGIN_ERROR');
        }

        // 查询数据库
        $user = UserModel::where('username', $username)->find();
        if (!$user) {
            return $this->error('USER_NOT_EXIST');
        }

        // 验证密码
        if (encryptPassword($password, $user->salt) != $user->password) {
            return $this->error('USER_LOGIN_ERROR');
        }

        // 生成token
        $authInfo = array(
            'uid' => $user->id,
            'gid' => $user->group_id,
        );

        $token = JWTAuth::builder($user->toArray());

        // 返回结果
        return $this->success(['token' => $token]);
    }
}
