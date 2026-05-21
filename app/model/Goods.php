<?php
namespace app\model;

use think\Model;

class Goods extends Model
{
    public static function getByType(int $type, $start = 0, $length = 10)
    {
        $list = self::where('is_del=0 and is_show=1 and minprice<>0 and type_id=' . $type)->order('ordid desc,add_time desc')->limit($start, $length)->select();
        return $list->toArray();
    }
}
