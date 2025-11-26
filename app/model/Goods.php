<?php
namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

class Goods extends Model
{
    use SoftDelete;
    protected $defaultSoftDelete = 0;
    protected $autoWriteTimestamp = true;
    protected $hidden = ['create_time', 'delete_time'];

    public function setCostAttr($value)
    {
        return $value * 100;
    }

    public function getCostAttr($value)
    {
        return $value / 100;
    }
}
