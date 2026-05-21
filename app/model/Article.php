<?php
namespace app\model;

use think\Model;

class Article extends Model
{
    public static function getList(int $num)
    {
        $list = self::field('id,title,add_time')->where('is_del=0 and cate_id=1')->order('ordid desc,add_time desc')->limit($num)->select();
        return $list->toArray();
    }
}
