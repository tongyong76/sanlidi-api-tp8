<?php
declare (strict_types = 1);

namespace app\controller\u;

use app\model\Goods as GoodsModel;
use app\utils\FormatArray;

class Goods extends BaseController
{
    //首页特价
    public function getIndexTj()
    {
        $tj = GoodsModel::where('is_del=0 and is_show=1 and switch like "%2%"')->order('ordid desc')->limit(2)->select();
        $tj = FormatArray::withPinyin($tj->toArray());

        return $this->success($tj);
    }

    //首页热卖
    public function getIndexHot()
    {
        $hot = GoodsModel::where('is_del=0 and is_hot=1 and minprice <> 0')->order('ordid desc,add_time desc')->limit(5)->select();
        $hot = FormatArray::withPinyin($hot->toArray());
        foreach ($hot as $key => $value) {
            $hot[$key]['info']   = FormatArray::msubstr(strip_tags($value['info']), 45);
            $hot[$key]['switch'] = json_decode($value['switch']);
        }

        return $this->success($hot);
    }

    //首页楼层
    public function getIndexFloor()
    {
        $list             = [];
        $list['zhoubian'] = GoodsModel::getByType(1, 0, 11);
        $list['guonei']   = GoodsModel::getByType(2, 0, 11);
        $list['chujing']  = GoodsModel::getByType(3, 0, 11);

        return $this->success($list);
    }
}
