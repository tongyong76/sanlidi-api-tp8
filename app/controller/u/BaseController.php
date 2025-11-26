<?php
declare (strict_types = 1);

namespace app\controller\u;

use app\BaseController as Base;
use app\utils\Response;
use think\Request;

class BaseController extends Base
{
    use Response;

    public function __construct(Request $request)
    {
        // 检查站点状态
        // $this->checkSite();

        $this->request = $request;
        $this->initialize();
    }

    public function initialize()
    {

    }
}
