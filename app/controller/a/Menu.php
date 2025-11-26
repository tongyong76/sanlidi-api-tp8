<?php
declare (strict_types = 1);

namespace app\controller\a;

use app\model\Menu as MenuModel;
use think\facade\Request;
use think\response\Json;

class Menu extends BaseController
{
    public function getActiveMenus(): Json
    {
        // gid
        $gid = Session('authInfo.gid');
        $menus = $this->__getMenus($gid);

        return $this->success($menus);
    }

    public function getTable()
    {
        // 完善参数
        $menus = MenuModel::getMenuTable();
        return $this->success($menus);
    }

    public function add()
    {
        $postData = Request::post();
        $data = [
            'name' => $postData['name'],
            'icon' => $postData['icon'],
            'path' => $postData['path'],
            'sort' => $postData['sort'],
            'pid' => $postData['pid'],
            'is_enable' => $postData['is_enable'],
        ];
        $res = MenuModel::create($data);
        return $this->success($res);
    }

    public function edit($id)
    {
        $postData = Request::post();
        $data = [
            'name' => $postData['name'],
            'icon' => $postData['icon'],
            'path' => $postData['path'],
            'sort' => $postData['sort'],
            'pid' => $postData['pid'],
            'is_enable' => $postData['is_enable'],
        ];
        $res = MenuModel::update($data, ['id' => $id]);
        return $this->success($res);
    }

    public function delete($id)
    {
        // 判断是否为空目录
        $childrenCount = MenuModel::where('pid', $id)->count();
        if ($childrenCount) {
            return $this->error('子菜单非空，请先删除子菜单');
        }

        // 删除
        $res = MenuModel::destroy($id);
        return $this->success($res);
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
}
