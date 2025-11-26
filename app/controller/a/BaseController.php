<?php
declare (strict_types = 1);

namespace app\controller\a;

use app\BaseController as Base;
use app\Request;
//use think\Request;
use app\utils\Response;

class BaseController extends Base
{
    use Response;
    protected $request;

    public function __construct(Request $request)
    {
        // 检查站点状态
        // $this->checkSite();

        $this->request = $request;
        // dump($this->request->testfn());

        $this->initialize();
    }

    public function initialize()
    {

    }
}
