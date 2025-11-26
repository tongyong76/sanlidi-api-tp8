<?php
declare (strict_types = 1);

namespace app\controller\a;

use app\model\User as UserModel;
use think\facade\Request;

class User extends BaseController
{
    public function changePassword()
    {
        $id = Session('authInfo')['uid'];
        $postData = Request::post();
        $password = $postData['password'];

        $user = UserModel::find($id);
        $newPassWord = encryptPassword($password, $user->salt);
        $newData = [
            'id' => $id,
            'password' => $newPassWord,
        ];
        $res = UserModel::update($newData);
        return $this->success($res);
    }

    // 获取管理员登录信息
    public function getInfo()
    {
        $id = Session('authInfo')['uid'];
        $user = UserModel::field('id,avatar,username')->find($id);
        return $this->success($user);
    }
}
