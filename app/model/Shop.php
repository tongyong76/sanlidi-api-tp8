<?php
declare (strict_types = 1);

namespace app\model;

use think\model;

class Shop extends model
{
    public function children()
    {
        return $this->hasMany(Room::class);
    }

    // 勿删，冗余
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
