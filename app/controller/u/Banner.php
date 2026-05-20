<?php
declare (strict_types = 1);

namespace app\controller\u;

use app\model\Banner as BannerModel;
use app\utils\BuildTree;

class Banner extends BaseController
{
    //首页幻灯调用
    public function getIndexBanner()
    {
        $bannerList = BannerModel::where('is_show=1 and is_del=0')->order('ordid desc')->select();
        $bannerList = BuildTree::build($bannerList->toArray());

        return $this->success($bannerList);
    }
}
