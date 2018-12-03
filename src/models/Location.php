<?php
namespace TapItTest\Models;

use TapItTest\App;

class Location
{
    private static $table = 'locations';

    public static function save($id, Gprmc $gprmc)
    {
        $sql = "insert into {self::table} values(DEFAULT, :device_id, :LatLong, :datetime)";
        $stmt = App::db()->getDbh()->prepare($sql);
        $stmt->execute([
            ':device_id' => $id,
            ':LatLong' => $gprmc->getLatLongString(),
            ':datetime' => "{$gprmc->getDate()} {$gprmc->getTime()}",
        ]);
    }
}
