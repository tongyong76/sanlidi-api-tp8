<?php
declare (strict_types = 1);

namespace app\controller\a;

use app\model\City as CityModel;
use think\facade\Request;

class City extends BaseController
{
    public function getListAll()
    {
        // 完善参数
        $cities = CityModel::getCitiesTree();
        return $this->success($cities);
    }

    public function getInfo($id)
    {
        $city = CityModel::find($id);
        return $this->success($city);
    }

    public function add()
    {
        $postData = Request::post();
        $data = [
            'pid' => $postData['pid'],
            'name' => $postData['name'],
            'merger_name' => $postData['merger_name'],
            'level' => ++$postData['plevel'],
            'lng' => $postData['lng'],
            'lat' => $postData['lat'],
            'is_enable' => $postData['is_enable'],
        ];
        $res = CityModel::create($data);
        return $this->success($res);
    }

    public function edit($id)
    {
        $postData = Request::post();
        $data = [
            'pid' => $postData['pid'],
            'name' => $postData['name'],
            'merger_name' => $postData['merger_name'],
            'level' => ++$postData['plevel'],
            'lng' => $postData['lng'],
            'lat' => $postData['lat'],
            'is_enable' => $postData['is_enable'],
        ];
        $res = CityModel::update($data, ['id' => $id]);
        return $this->success($res);
    }

    public function delete($id)
    {
        $childrenCount = CityModel::where('pid', $id)->count();
        if ($childrenCount) {
            return $this->error('子菜单非空，请先删除子菜单');
        }

        // 删除
        $res = CityModel::destroy($id);
        return $this->success($res);
    }
}
