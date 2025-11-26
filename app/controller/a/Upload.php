<?php
declare (strict_types = 1);

namespace app\controller\a;

use app\service\Upload as UploadService;

/**
 * 图片上传
 */
class Upload extends BaseController
{
    /**
     * 上传(不存入相册)
     */
    public function index()
    {
        // $file = request()->file('image');
        // if (empty($file)) {
        //     return $this->error('上传失败');
        // }
        // $savename = \think\facade\Filesystem::disk('public')->putFile('images', $file);

        // return $this->success($savename);

        $upload = new UploadService();
        return $upload->simple();
    }
}
