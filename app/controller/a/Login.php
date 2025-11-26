<?php
declare (strict_types = 1);

namespace app\controller\a;

use app\model\Menu as MenuModel;
use app\model\User as UserModel;
use thans\jwt\facade\JWTAuth;
use think\facade\Request;
use think\response\Json;

/**
 * 登录相关
 */
class Login extends BaseController
{
    /**
     * 手机号登录
     * @param phone sting
     * @param password string MD5加密字符串
     * @return reponse Json
     */
    public function login(): Json
    {
        // 1.接收参数
        $post = Request::post();

        // 2.校验参数
        $phone = $post['phone'] ? $post['phone'] : '';
        $password = $post['password'] ? $post['password'] : '';
        if ($phone == '' || $password == '') {
            return $this->error('USER_LOGIN_ERROR');
        }

        // 3.查询数据库
        $user = UserModel::where('phone', $phone)->find();
        if (!$user) {
            return $this->error('USER_NOT_EXIST');
        }

        // 4.验证密码
        if (encryptPassword($password, $user->salt) != $user->password) {
            return $this->error('USER_LOGIN_ERROR');
        }

        // 5.生成token
        $authInfo = array(
            'uid' => $user->id,
            'gid' => $user->group_id,
        );

        $token = JWTAuth::builder($authInfo);

        // 6.返回结果
        return $this->success(['token' => $token]);
    }

    /**
     * 登陆初始化
     */
    public function loginInit()
    {
        $data = array();
        $uid = Session('authInfo.uid');
        $gid = Session('authInfo.gid');

        if (!$uid || !$gid) {
            return $this->error('USER_NOT_LOGIN');
        }

        // 获取管理员信息
        $data['adminInfo'] = $this->__getAdminInfo($uid);
        // 获取菜单
        $data['menus'] = $this->__getMenus($gid);
        $data['rules'] = $this->__getRules($gid);
        // 返回data{}

        return $this->success($data);
    }

    /**
     * 获取登录用户信息
     * @param int $uid
     * @return array
     */
    private function __getAdminInfo($uid): array
    {
        // 1.用户信息，头像、昵称
        // 2.用户权限，树状菜单、权限菜单ids
        return $user = UserModel::field('avatar,phone')->find($uid)->toArray();
    }

    /**
     * 获取菜单树
     */
    private function __getMenus($gid): array
    {
        $menus = null;
        //超级管理员
        if ($gid == 1) {
            $menus = MenuModel::getAdminMenuTree();
        }
        return $menus;
    }

    /**
     * 获取权限ids
     */
    private function __getRules($gid): array
    {
        $ids = null;
        //超级管理员
        if ($gid == 1) {
            $ids = MenuModel::getAdminMenuIDS();
        }
        return $ids;
    }
}
