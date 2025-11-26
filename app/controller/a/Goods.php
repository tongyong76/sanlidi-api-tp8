<?php
declare (strict_types = 1);

namespace app\controller\a;

use app\model\Goods as GoodsModel;
use think\facade\Request;

class Goods extends BaseController
{
    public function getListByPage(int $page = 1, int $limit = 10)
    {
        $param = Request::get();
        $start = ($page - 1) * $limit;
        $list = GoodsModel::where(1)->order('sort desc,id asc')->limit($start, $limit)->select();
        $count = GoodsModel::where(1)->count();
        return $this->success(['list' => $list->toArray(), 'total' => $count, 'param' => $param]);
    }

    public function getAll()
    {
        $list = GoodsModel::field('id,name,cost')->select();
        return $this->success($list);
    }

    public function getInfo($id)
    {
        $goods = GoodsModel::find($id);
        return $this->success($goods);
    }

    public function add()
    {
        $postData = Request::post();
        $data = [
            'cate_id' => $postData['cate_id'],
            'name' => $postData['name'],
            'imgurl' => $postData['imgurl'],
            'cost' => $postData['cost'],
            'sort' => $postData['sort'],
            'is_enable' => $postData['is_enable'],
        ];
        $res = GoodsModel::create($data);
        return $this->success($res);
    }

    public function edit($id)
    {
        $postData = Request::post();
        $data = [
            'cate_id' => $postData['cate_id'],
            'name' => $postData['name'],
            'imgurl' => $postData['imgurl'],
            'cost' => $postData['cost'],
            'sort' => $postData['sort'],
            'is_enable' => $postData['is_enable'],
        ];
        $res = GoodsModel::update($data, ['id' => $id]);
        return $this->success($res);
    }

    public function delete($id)
    {
        $res = GoodsModel::destroy($id);
        return $this->success($res);
    }
}
