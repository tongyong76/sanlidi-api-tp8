<?php
declare (strict_types = 1);

namespace app\controller\u;

use app\model\Shop as ShopModel;

class Shop extends BaseController
{
    public function getList()
    {
        $list = ShopModel::with('rooms')->select();
        return $this->success($list);
    }
}
