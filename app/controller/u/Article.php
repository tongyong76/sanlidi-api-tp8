<?php
declare (strict_types = 1);

namespace app\controller\u;

use app\model\Article as ArticleModel;

class Article extends BaseController
{
    //首页新闻调用
    public function getIndexNews()
    {
        $list = ArticleModel::getList(3);

        return $this->success($list);
    }
}
