<?php
declare (strict_types = 1);

namespace app\controller\a;

use app\model\Order as OrderModel;
use think\facade\Request;

class Statistic extends BaseController
{
    // 订单基础
    public function getOrderBasic()
    {
        $param = Request::get();
        $start = $param['start'] ? strtotime($param['start']) : 0;
        $end = $param['end'] ? strtotime($param['end']) + 3600 * 24 : 0;
        // $now = time();

        if (!$start || !$end) {
            return $this->error('参数错误');
        }

        if ($start > $end) {
            return $this->error('参数错误');
        }

        $data['order_amount'] = OrderModel::sum([['order_date', 'between', [$start, $end]]], 'order_price');
        $data['order_count'] = OrderModel::where([['order_date', 'between', [$start, $end]]])->count();
        $data['refund_amount'] = OrderModel::sum([['order_status', 'in', [-1, -4]], ['order_date', 'between', [$start, $end]]], 'order_price');
        $data['refund_count'] = OrderModel::where([['order_status', 'in', [-1, -4]], ['order_date', 'between', [$start, $end]]])->count();

        return $this->success($data);
    }

    // 订单趋势
    public function getOrderTrend()
    {
        // 订单趋势
        $param = Request::get();
        $start = $param['start'] ? strtotime($param['start']) : 0;
        $end = $param['end'] ? strtotime($param['end']) + 3600 * 24 : 0;
        $data = [];

        if (!$start || !$end) {
            return $this->error('参数错误');
        }

        if ($start > $end) {
            return $this->error('参数错误');
        }

        // 初始化
        $start = $end - 3600 * 24 * 30;
        $data['xAxis'] = [];
        $arr = [];
        for ($i = 0; $i < 30; $i++) {
            $day = date('m/d', $start + $i * 3600 * 24);
            $arr[$day]['count'] = 0;
            $arr[$day]['sum'] = 0;
        }
        $where = [['order_date', 'between', [$start, $end]]];

        // 订单量
        $list = OrderModel::where($where)->select();
        foreach ($list as $item) {
            $day = date('m/d', strtotime($item['order_date']));
            $arr[$day]['count']++;
            $arr[$day]['sum'] += $item['order_price'];
        }

        foreach ($arr as $key => $item2) {
            $data['count'][] = $item2['count'];
            $data['sum'][] = round($item2['sum'], 2);
            $data['xAxis'][] = $key;
        }

        return $this->success($data);
    }

    // 订单累计
    public function getOrderAccumulate()
    {
        $param = Request::get();
        $start = $param['start'] ? strtotime($param['start']) : 0;
        $end = $param['end'] ? strtotime($param['end']) + 3600 * 24 : 0;
        $data = [];
        $dailyRecord = [];

        $start = $end - 3600 * 24 * 120;
        $arr = [];
        for ($i = 0; $i < 120; $i++) {
            $day = date('m/d', $start + $i * 3600 * 24);
            $arr[$day]['sum'] = 0;
        }
        $where = [['order_date', 'between', [$start, $end]]];
        $list = OrderModel::where($where)->select();
        foreach ($list as $item) {
            $day = date('m/d', strtotime($item['order_date']));
            $arr[$day]['sum'] += $item['order_price'];
        }

        foreach ($arr as $item2) {
            $dailyRecord[] = round($item2['sum'], 2);
        }

        // 计算最近30天累计营业额
        $data['rollingSum'] = [];
        for ($i = 0; $i < count($dailyRecord); $i++) {
            if ($i < 30) {
                // 前30天，不处理
            } else {
                // 第31天及以后，累计最近30天的营业额
                $data['rollingSum'][] = round(array_sum(array_slice($dailyRecord, $i - 29, 30)), 2);
            }
        }

        $data['xAxis'] = [];
        for ($i = 0; $i < 90; $i++) {
            $data['xAxis'][] = date('m/d', $start + ($i + 30) * 3600 * 24);
        }

        return $this->success($data);
    }

    // 商品排行
    public function getGoodsRank()
    {
        return $this->success('getGoodsRank');

    }

}
