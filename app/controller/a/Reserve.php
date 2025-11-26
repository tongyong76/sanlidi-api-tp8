<?php
declare (strict_types = 1);

namespace app\controller\a;

use app\model\Reserve as ReserveModel;
use think\facade\Request;

class Reserve extends BaseController
{
    public function getListByPage(int $page = 1, int $limit = 10)
    {
        $param = Request::get();
        $start = ($page - 1) * $limit;
        $list = ReserveModel::where(1)->order('order_day desc')->limit($start, $limit)->select();
        $newList = $this->__formatReserveList($list->toArray());
        $count = ReserveModel::where(1)->order('order_day desc')->count();
        return $this->success(['list' => $newList, 'total' => $count, 'param' => $param]);
    }

    public function add()
    {
        $postData = Request::post();
        if (isset($postData['order_day'])) {
            $postData['order_day'] = strtotime($postData['order_day']);
        }

        $allowField = [
            'shop_id',
            'shop_name',
            'shop_room',
            'order_day',
            'order_time_str',
            'order_time',
            'order_user',
            'order_phone',
            'order_number',
        ];
        $res = ReserveModel::create($postData, $allowField);
        return $this->success($res);
    }

    public function edit()
    {
        $postData = Request::post();
        $newData = [
            'id' => $postData['id'],
            'shop_id' => $postData['shop_id'],
            'shop_name' => $postData['shop_name'],
            'shop_room' => $postData['shop_room'],
            'order_day' => strtotime($postData['order_day']),
            'order_time_str' => $postData['order_time_str'],
            'order_time' => $postData['order_time'],
            'order_user' => $postData['order_user'],
            'order_phone' => $postData['order_phone'],
            'order_number' => $postData['order_number'],
        ];
        $res = ReserveModel::update($newData);
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
