<?php
namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

class Menu extends Model
{
    use SoftDelete;
    protected $defaultSoftDelete = 0;
    protected $autoWriteTimestamp = true;
    protected $hidden = ['create_time', 'delete_time'];

    public function getIsEnableAttr($value)
    {
        $status = [1 => true, 0 => false];
        return $status[$value];
    }

    public static function getAdminMenuTree()
    {
        $menus = self::field('id,pid,name,icon,path')->where('is_enable', 1)->select();
        $menuTree = toTree($menus->toArray());

        return $menuTree;
    }

    public static function getMenuTable()
    {
        $menus = self::field('id,pid,name,icon,path,sort,is_enable')->select();
        $menuTable = toTree($menus->toArray());

        return $menuTable;
    }

    // 删除，该功能由前端完成 2025-05-16
    // public static function getAdminMenuIDS()
    // {
    //     $ids = self::where('status', 1)->column('id');

    //     return $ids;
    // }
}
