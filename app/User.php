<?php

namespace App;

use core\database\model\Model;

class User extends Model
{

    // 指定表名
    protected $table = 'keywords';

    public function index()
    {
        echo "Hello PHP";
    }

    public function say()
    {
        return "id = {$this->id} 名字: {$this->keyword}";
    }
}
