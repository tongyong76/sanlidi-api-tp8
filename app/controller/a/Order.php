<?php
declare (strict_types = 1);

namespace app\controller\a;

use app\model\Order as OrderModel;
use app\model\OrderGoods as OrderGoodsModel;
use think\facade\Request;

class Order extends BaseController
{
    public function getListByPage(int $page = 1, int $limit = 10)
    {
        $param = Request::get();
        $where = [];
        $start = ($page - 1) * $limit;

        // orderSrc: store.order.orderSrc == 'all' ? '' : store.order.orderSrc,
        // timeRange: store.order.timeRange,
        // searchKey: store.order.searchKey,
        // searchValue: store.order.searchValue,
        $orderSrc = $param['orderSrc'] ?? '';
        $orderSrc = $orderSrc == 'all' ? '' : $orderSrc;
        if ($orderSrc) {
            $where[] = ['pt', '=', $orderSrc];
        }
        $searchKey = $param['searchKey'] ?? '';
        $searchValue = $param['searchValue'] ?? '';
        if ($searchKey && $searchValue) {
            $where[] = [$searchKey, 'like', '%' . $searchValue . '%'];
        }
        $timeRange = $param['timeRange'] ?? '';
        if ($timeRange['start'] && $timeRange['end']) {
            $where[] = ['order_date', 'between time', [$timeRange['start'], $timeRange['end']]];
        }
        $status = $param['status'] ?? '';
        $status = $status == 'all' ? '' : $status;
        if ($status) {
            $where[] = ['order_status', '=', $status];
        }

        $field = ['id', 'pt', 'sn', 'order_date', 'order_price', 'order_status', 'order_user', 'order_zone', 'order_address'];
        $list = OrderModel::with('goods')->field($field)->where($where)->limit($start, $limit)->order('order_date desc')->select();
        $list->hidden(['goods' => ['cate_id', 'imgurl', 'is_enable', 'update_time', 'sort']]);
        $list->toArray();
        $count = OrderModel::where($where)->count();
        return $this->success(['list' => $list, 'total' => $count, 'param' => $param]);
    }

    public function getDetail(int $id)
    {
        return $this->success('getDetail:' . $id);
    }

    public function add()
    {
        $postData = Request::post();
        $data = [
            'pt' => $postData['pt'],
            'sn' => $postData['sn'],
            'snapshot' => json_encode($postData['goods_list']),
            'order_date' => strtotime($postData['order_date']),
            'order_status' => 1,
            'order_price' => $postData['order_price'],
            'order_status' => $postData['order_status'],
            'order_user' => $postData['order_user'],
            'order_zone' => json_encode($postData['order_zone']),
            'order_address' => $postData['order_address'],
        ];
        $res = OrderModel::create($data);
        $this->__addOrderGoods($res->id, $postData['goods_list']);

        return $this->success($res);
    }

    public function edit(int $id)
    {
        $postData = Request::post();
        $data = [
            'pt' => $postData['pt'],
            'sn' => $postData['sn'],
            'snapshot' => json_encode($postData['goods']),
            'order_date' => strtotime($postData['order_date']),
            'order_status' => 1,
            'order_price' => $postData['order_price'],
            'order_status' => $postData['order_status'],
            'order_user' => $postData['order_user'],
            'order_zone' => json_encode($postData['order_zone']),
            'order_address' => $postData['order_address'],
        ];
        $res = OrderModel::update($data, ['id' => $id]);
        $this->__addOrderGoods($id, $postData['goods_list']);
        return $this->success($data);
    }

    public function delete(int $id)
    {
        $res = OrderModel::destroy($id);
        return $this->success($res);
    }

    // 订单发货
    public function setDeliver($id)
    {
        $postData = Request::post();
        $order = OrderModel::find($id);

        // 订单发货
        if ($order && $order->order_status == 1) {
            $data = [
                'delivery_id' => $postData['sendOrderDeliveryId'],
                'delivery_name' => $postData['sendOrderDeliveryName'],
                'order_status' => 2,
            ];
            $res = OrderModel::update($data, ['id' => $id]);
            return $this->success($res);
        }

        // 修改单号
        if ($order && $order->order_status == 2) {
            $data = [
                'delivery_id' => $postData['sendOrderDeliveryId'],
                'delivery_name' => $postData['sendOrderDeliveryName'],
            ];
            $res = OrderModel::update($data, ['id' => $id]);
            return $this->success($res);
        }

        // 其他情况
        return $this->error('修改失败');

    }

    // 获取快递信息
    public function getDeliver($id)
    {
        $order = OrderModel::find($id);
        if (!$order) {
            return $this->error('订单不存在');
        }
        $delivery = [
            'id' => $order->id,
            'deliveryId' => $order->delivery_id,
            'deliveryName' => $order->delivery_name,
        ];

        return $this->success($delivery);
    }

    // 订单确认收货
    public function setDone($id)
    {
        $order = OrderModel::find($id);
        if (!$order || $order->order_status != 2) {
            return $this->error('订单状态错误');
        }
        $data = [
            'order_status' => 3,
        ];
        $res = OrderModel::update($data, ['id' => $id]);
        return $this->success($res);
    }

    // 订单取消->订单删除
    // 修改时间：20250807
    public function setCancel($id)
    {
        // $order = OrderModel::find($id);
        // if (!$order || $order->order_status != 1) {
        //     return $this->error('订单状态错误');
        // }
        // $data = [
        //     'order_status' => -1,
        // ];
        // $res = OrderModel::update($data, ['id' => $id]);
        // return $this->success($res);
        $res = OrderModel::destroy($id);
        return $this->success($res);
    }

    public function __addOrderGoods($oid, $goods_list)
    {
        // 1.清空历史记录
        OrderGoodsModel::where('order_id', $oid)->delete();
        // 2.写入新记录
        $newData = array();
        foreach ($goods_list as $item) {
            $newData[] = [
                'order_id' => $oid,
                'goods_id' => $item['id'],
                'count' => $item['count'],
            ];
        }
        OrderGoodsModel::insertAll($newData);
    }
}
