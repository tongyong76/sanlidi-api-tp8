<?php
declare (strict_types = 1);

namespace app\controller\a;

use app\model\Order as OrderModel;
use app\model\Refund as RefundModel;
use think\facade\Request;

class Refund extends BaseController
{

    // Route::get('refund/list', 'a.Refund/getListByPage');
    // Route::get('refund/:id', 'a.Refund/getDetail');
    // Route::post('refund/add', 'a.Refund/add');
    // Route::put('refund/:id/update', 'a.Refund/edit');
    // Route::delete('refund/:id', 'a.Refund/delete');

    public function getListByPage()
    {
        $postData = Request::post();
        $list = RefundModel::select();
    }

    public function getDetail($id)
    {
        $info = RefundModel::find($id);
        return $this->success($info);
    }

    public function add()
    {
        $postData = Request::post();
        switch ($postData['refund_type']) {
            case '1':
                $refund_status = '-1';
                break;
            case '2':
                $refund_status = '1';
                break;
            case '3':
                $refund_status = '1';
                break;
            case '4':
                $refund_status = '-1';
                break;
        }

        $data = [
            'order_sn' => $postData['order_sn'],
            'order_id' => $postData['order_id'],
            'user_id' => $postData['user_id'],
            'refund_date' => strtotime($postData['refund_date']),
            'refund_price' => $postData['refund_price'],
            'refund_type' => $postData['refund_type'],
            'refund_status' => $refund_status,
            'refund_delivery_name' => $postData['refund_delivery_name'],
            'refund_delivery_id' => $postData['refund_delivery_id'],
            'info' => $postData['info'],
        ];
        $res = RefundModel::create($data);

        // 同步更新订单状态为（-4已售后）
        if ($res) {
            OrderModel::update(['order_status' => -4], ['id' => $postData['order_id']]);
        }

        return $this->success($res);
    }

    public function edit($id)
    {
        $postData = Request::post();
        $res = RefundModel::update($postData, ['id' => $id]);
        return $this->success($res);

    }

}
