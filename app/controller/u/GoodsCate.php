<?php
declare (strict_types = 1);

namespace app\controller\u;

use app\model\GoodsCate as GoodsCateModel;
use app\utils\BuildTree;

class GoodsCate extends BaseController
{
    //首页热门分类
    public function getIndexHot()
    {
        $cates = GoodsCateModel::field('id,pid,name,pinyin')->where('is_del=0 and is_show=1')->order('ordid desc')->select();
        $cates = BuildTree::build($cates->toArray());

        return $this->success($cates);
    }
}
