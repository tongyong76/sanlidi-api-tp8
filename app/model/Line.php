<?php
declare (strict_types = 1);

namespace app\model;

use think\model;
use think\model\concern\SoftDelete;

class Line extends model
{
    use SoftDelete;
    protected $defaultSoftDelete  = 0;
    protected $autoWriteTimestamp = true;
    protected $hidden             = ['create_time', 'delete_time'];
}
