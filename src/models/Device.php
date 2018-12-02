<?php

namespace TapItTest\Models;

use TapItTest\App;

class Device
{
    private $table = 'devices';

    function create($name)
    {
        $sql = "insert into {$this->table} values(DEFAULT, :name)";
        $stmt = App::db()->getDbh()->prepare($sql);
        $stmt->execute([':name' => $name]);
    }

}