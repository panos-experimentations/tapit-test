<?php

namespace TapItTest\Models;

use TapItTest\App;

class Device
{
    private static $table = 'devices';

    /**
     * will create a device with the name the same as the id
     * @param $id
     */
    static function create($id)
    {
        $sql = "insert into {self::table} values(:id, :name)";
        $stmt = App::db()->getDbh()->prepare($sql);
        $stmt->execute([ ':id' => $id, ':name' => $id]);
    }

    static function setName($id, $name)
    {
        $sql = "update {self::table} set name = :name where id = :id";
        $stmt = App::db()->getDbh()->prepare($sql);
        $stmt->execute([ ':id' => $id, ':name' => $id]);
    }

}