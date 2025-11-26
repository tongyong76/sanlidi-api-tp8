<?php
declare (strict_types = 1);

namespace app\controller\u;

use app\model\Reserve as ReserveModel;
use app\model\Shop as ShopModel;
use think\facade\Request;

class Reserve extends BaseController
{
    public function getListByPage(int $page = 1, int $limit = 10)
    {
        $start = ($page - 1) * $limit;
        $list = ReserveModel::where(1)->order('order_day desc')->limit($start, $limit)->select();
        $newList = $this->__formatReserveList($list->toArray());
        return $this->success($newList);
    }
    public function getListByDate()
    {
        $post = Request::post();
        $day = strtotime($post['day']);
        $list = ReserveModel::where('order_day', $day)->order('order_day asc')->select();
        $newList = $this->__formatReserveList($list->toArray());
        return $this->success($newList);
    }

    public function getDetail($id)
    {
        $reserveInfo = ReserveModel::find($id);
        $shopInfo = ShopModel::find($reserveInfo->shop_id);

        $res = array(
            'reserveInfo' => $reserveInfo,
            'shopInfo' => $shopInfo,
        );

        return $this->success($res);
    }

    public function add()
    {
        $post = Request::post();
        $addData = array(
            'shop_id' => $post['shopId'],
            'shop_name' => $post['shopName'],
            'shop_room' => $post['shopRoom'],
            'order_day' => strtotime($post['orderDay']),
            'order_time' => $post['orderTime'],
            'order_time_str' => $post['orderTimeStr'],
            'order_user' => $post['orderUser'],
            'order_phone' => $post['orderPhone'],
            'order_number' => $post['orderNumber'],
            'order_type' => $post['orderType'],
        );
        $res = ReserveModel::create($addData);
        return $this->success($res);
    }

    public function edit($id)
    {
        $post = Request::post();
        $editData = array(
            'id' => $id,
            'shop_id' => $post['shopId'],
            'shop_name' => $post['shopName'],
            'shop_room' => $post['shopRoom'],
            'order_day' => strtotime($post['orderDay']),
            'order_time' => $post['orderTime'],
            'order_time_str' => $post['orderTimeStr'],
            'order_user' => $post['orderUser'],
            'order_phone' => $post['orderPhone'],
            'order_number' => $post['orderNumber'],
            'order_type' => $post['orderType'],
        );
        $res = ReserveModel::update($editData);
        return $this->success($res);
    }

    public function delete($id)
    {
        $res = ReserveModel::destroy($id);
        return $this->success($res);
    }

    private function __formatReserveList($data)
    {
        foreach ($data as $key => $value) {
            if ($value['order_day'] < strtotime(date("Y-m-d"))) {
                $data[$key]['status'] = 0;
            } else {
                $data[$key]['status'] = 1;
            }
            $data[$key]['order_day_short'] = date('m-d', $value['order_day']);
            $data[$key]['order_day'] = date('Y-m-d', $value['order_day']);

        }
        return $data;
    }
}
