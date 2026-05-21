<?php
declare (strict_types = 1);

namespace app\controller\u;

use app\model\Ad as AdModel;

class Ad extends BaseController
{
    //首页新闻调用
    public function getAll()
    {
        $nList = [];
        $list  = AdModel::where('is_del=0 and status =1')->select()->toArray();
        foreach ($list as $key => $value) {
            $nList[$value['cname']] = $value;
        }

        return $this->success($nList);
    }
}
