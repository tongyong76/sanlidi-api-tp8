<?php
declare (strict_types = 1);

namespace app\service;

use app\controller\a\BaseController;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
// use Intervention\Image\Drivers\Imagick\Driver;
use think\facade\Request;

/**
 * 公共上传类
 * 2022-03-07 23：16
 */
class Upload extends BaseController
{
    private $file;
    private $dir;

    public function __construct($file_name = 'image')
    {
        //获取图片上传配置文件
        // $config_model = new ConfigModel();
        // $config_result = $config_model::getConfig('IMAGE_UPLOAD_CONFIG');
        // $this->config = $config_result['config_value'];

        // 获取文件
        $this->file = request()->file($file_name);

        // $this->album_id = Request::post('album_id') ? trim(Request::post('album_id')) : 0;
        $this->dir = Request::post('dir') ? trim(Request::post('dir')) : 'images';
    }

    /**
     * 上传图片
     * @return string 图片途径
     */
    public function simple()
    {

        // $file = request()->file('image');
        // if (empty($file)) {
        //     return $this->error('上传失败');
        // }
        // $savename = \think\facade\Filesystem::disk('public')->putFile('images', $file);

        // return $this->success($savename);

        if (empty($this->file)) {
            return $this->error('上传失败');
        }
        $file_path = \think\facade\Filesystem::disk('public')->putFile($this->dir, $this->file);
        $path = 'uploads/' . str_replace('\\', '/', $file_path);
        if (Request::post('thumbnail') == true) {
            $this->__resize($path);
        }

        return $this->success($path);

        // $check_res = $this->checkImage();
        // if ($check_res['errCode'] >= 0) {
        //     // 校验图片安全
        //     // return $this->checkImageSecurity();

        //     // 查看是否存在
        //     $file_exist = AttachmentModel::where('md5', '=', $this->file->md5())->where('sha1', $this->file->sha1())->find();
        //     if ($file_exist) {
        //         $image_data = array(
        //             'id' => $file_exist['id'],
        //             'path' => $file_exist['path'],
        //             'width' => $file_exist['width'],
        //             'height' => $file_exist['height'],
        //             'spec' => $file_exist['spec'],
        //         );
        //         return $this->success($image_data);
        //     }

        //     //  移动到框架应用根目录/uploads/ 目录下
        //     $file_path = \think\facade\Filesystem::disk('public')->putFile($this->dir, $this->file);
        //     $path = 'uploads/' . str_replace('\\', '/', $file_path);
        //     $img_info = getimagesize($path);

        //     if ($file_path) {
        //         $image_data = array(
        //             'path' => $path,
        //             'width' => $img_info[0],
        //             'height' => $img_info[1],
        //             'spec' => $img_info[0] . '*' . $img_info[1],
        //             'mime' => $this->file->getMime(),
        //             'ext' => $this->file->extension(),
        //             'size' => $_FILES['file']['size'],
        //             'md5' => $this->file->md5(),
        //             'sha1' => $this->file->sha1(),
        //         );
        //     }

        //     $res = AttachmentModel::create($image_data);
        //     if ($res) {
        //         return $this->success($res, "UPLOAD_SUCCESS");
        //     } else {
        //         return $this->error('', 'UNKNOW_ERROR');
        //     }
        // } else {
        //     return $check_res;
        // }
    }

    /**
     * 图片验证 - 系统上传规则
     * @param $file
     * @return \multitype
     */
    private function checkImage()
    {
        // $data['md5'] = $this->file->md5();
        // $data['sha1'] = $this->file->sha1();
        $data['mime'] = $this->file->getMime();
        $data['ext'] = $this->file->extension();
        // $data['name'] = $_FILES['file']['name'];
        $data['size'] = $_FILES['file']['size'];

        $size_rule = $this->config["max_filesize"] * 1000;
        $ext_rule = explode('|', $this->config["image_allow_ext"]);
        $mime_rule = explode('|', $this->config["image_allow_mime"]);

        if (!in_array($data['ext'], $ext_rule)) {
            return $this->error('', 'UPLOAD_FAIL_IMAGE');
        }
        if (!in_array($data['mime'], $mime_rule)) {
            return $this->error('', 'UPLOAD_FAIL_IMAGE');
        }
        if ($data['size'] > $size_rule) {
            return $this->error('', 'UPLOAD_FAIL_SIZE');
        }
        return $this->success();
    }

    /**
     * 校验图片是否含有违法违规内容
     * @param $file
     * @return \multitype
     */
    private function checkImageSecurity()
    {
        $media = new \CURLFile($_FILES['file']['tmp_name']);
        $Security = new Security();
        $res = $Security->imgSecCheck($media);
        return $this->success($res);
    }

    /**
     * 设置上传目录
     * @param $path
     */
    public function setPath($path)
    {
        if ($this->site_id > 0) {
            $this->path = $this->site_id . "/" . $path;
        } else {
            $this->path = $path;
        }
        $this->path = $this->upload_path . "/" . $this->path;
        return $this;
    }

    /**
     * 判断图片是否已经存在
     * @param $file
     */
    public function checkFileExist($file)
    {
        return 'checkFileExist';
    }

    public function getConfig()
    {
        return $this->config;
    }

    /**
     * 裁切图片
     */
    private function __resize($img_path)
    {
        // $manager = new ImageManager(Driver::class);
        // create image manager with desired driver
        $manager = new ImageManager(
            // new Intervention\Image\Drivers\Gd\Driver()
            new Driver()
        );

        // open an image file
        $image = $manager->read($img_path);

        // resize image instance
        $image->scale(width: 750);

        // insert a watermark
        //$image->place('images/watermark.png');

        // encode edited image
        $encoded = $image->toJpg();

        // save encoded image
        $encoded->save($img_path . '_thumbnail.jpg');
    }
}
