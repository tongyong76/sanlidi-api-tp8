<?php
namespace app\model;

use think\model;
use think\model\concern\SoftDelete;

class City extends model
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

    public static function getCitiesTree()
    {
        $cities = self::field('id,pid,name,level,merger_name,lng,lat,is_enable')->select();
        $citiesTree = toTree($cities->toArray());

        return $citiesTree;
    }
}
