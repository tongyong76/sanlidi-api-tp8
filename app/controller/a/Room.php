<?php
declare (strict_types = 1);

namespace app\controller\a;

use app\model\Room as RoomModel;
use think\facade\Request;

class Room extends BaseController
{
    public function edit()
    {
        $postData = Request::post();
        $newData = [
            'id' => $postData['id'],
            'shop_id' => $postData['shop_id'],
            'name' => $postData['name'],
        ];
        $res = RoomModel::update($newData);
        return $this->success($res);
    }

    public function add()
    {
        $postData = Request::post();
        $newData = [
            'shop_id' => $postData['shop_id'],
            'name' => $postData['name'],
        ];
        $res = RoomModel::create($newData);
        return $this->success($res);
    }

    public function delete($id)
    {
        $res = RoomModel::destroy($id);
        return $this->success($res);
    }
}
