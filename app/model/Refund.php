<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

class Refund extends model
{
    use SoftDelete;
    protected $autoWriteTimestamp = true;
    protected $hidden = ['create_time', 'delete_time', 'create_ip'];

    public function setRefundPriceAttr($value)

    {
        return $value * 100;
    }

    public function getRefundPriceAttr($value)

    {
        return $value / 100;
    }
}
