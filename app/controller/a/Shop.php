<?php
declare (strict_types = 1);

namespace app\controller\a;

use app\model\Shop as ShopModel;
use think\facade\Request;

class Shop extends BaseController
{
    public function getList()
    {
        $list = ShopModel::select();
        return $this->success($list);
    }

    public function edit()
    {
        $postData = Request::post();

        $newGps = explode(',', str_replace('，', ',', $postData['gps']));
        $newData = [
            'id' => $postData['id'],
            'name' => $postData['name'],
            'address' => $postData['address'],
            'latitude' => $newGps[0] * 1000000,
            'longitude' => $newGps[1] * 1000000,
            'gps' => $postData['gps'],
            'phone_shop' => $postData['phone_shop'],
            'phone_manager' => $postData['phone_manager'],
            'manager' => $postData['manager'],
            'park' => $postData['park'],
        ];
        $data = ShopModel::update($newData);
        return $this->success($data);
    }

    public function add()
    {

    }

    public function setImage()
    {
        $post = Request::post();
        $id = $post['id'];
        $images = $post['images'];
        $type = $post['type'];
        switch ($type) {
            case 'image_shop':
                $data = [
                    'id' => $id,
                    'image_shop' => $images,
                ];
                break;
            case 'image_food':
                $data = [
                    'id' => $id,
                    'image_food' => $images,
                ];
                break;
        }
        $res = ShopModel::update($data);
        if ($res) {
            return $this->success($res);
        } else {
            return $this->error('上传失败');
        }
    }

    public function getShopAndRoom()
    {
        $res = ShopModel::field('id,name')->with('children')->select();
        foreach ($res as $key => $value) {
            $res[$key]['id'] = $value['id'] + 100000;
        }
        return $this->success($res);
    }

    public function getOther($id)
    {
        $res = ShopModel::field('id,park,image_share_reserve,image_head_reserve,image_share_invitation,image_head_invitation')->where('id', $id)->find();
        return $this->success($res);
    }

    //更新图片信息
    public function setOther()
    {
        $postData = Request::post();
        $newData['id'] = $postData['id'];
        if ($postData['park']) {
            $newData['park'] = $postData['park'];
        }
        if ($postData['image_share_reserve']) {
            $newData['image_share_reserve'] = $postData['image_share_reserve'];
        }
        if ($postData['image_head_reserve']) {
            $newData['image_head_reserve'] = $postData['image_head_reserve'];
        }
        if ($postData['image_share_invitation']) {
            $newData['image_share_invitation'] = $postData['image_share_invitation'];
        }
        if ($postData['image_head_invitation']) {
            $newData['image_head_invitation'] = $postData['image_head_invitation'];
        }
        $data = ShopModel::update($newData);
        return $this->success($data);
    }
}
