<?php
declare (strict_types = 1);

namespace app\exception;

use app\utils\Code;
use think\Exception;

class FailedException extends Exception
{
    protected $code = Code::FAILED;
}
