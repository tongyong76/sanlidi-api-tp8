<?php
declare (strict_types = 1);

namespace app\model;

use think\model;
use think\model\concern\SoftDelete;

class Order extends model
{
    use SoftDelete;
    protected $defaultSoftDelete = 0;
    protected $autoWriteTimestamp = true;
    protected $hidden = ['create_time', 'delete_time', 'create_ip'];

    public function setOrderPriceAttr($value)
    {
        return $value * 100;
    }

    public function getOrderPriceAttr($value)
    {
        return $value / 100;
    }

    public function getOrderDateAttr($value)
    {
        return date('Y-m-d H:i', $value);
    }

    public function getOrderZoneAttr($value)
    {
        if ($value) {
            return json_decode($value, true);
        }
    }

    public function goods()
    {
        return $this->belongsToMany(Goods::class, 'order_goods', 'goods_id', 'order_id');
    }

    /**
     * 求和
     * @param array $where
     * @param string $field
     * @return float
     * @throws \ReflectionException
     */
    public static function sum(array $where, string $field)
    {
        return self::where($where)->sum($field) / 100;
    }
}
