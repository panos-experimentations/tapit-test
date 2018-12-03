<?php

namespace TapItTest;


use TapItTest\Models\Device;
use TapItTest\Models\Gprmc;
use TapItTest\Models\Location;

class Service
{

    public static function payload($data_packet): void
    {
        list($id, $gprmc_sentence, $device_name) = \TapItTest\Util::parseTcpDataPackage($data_packet);

        // save location
        if ($gprmc_sentence) {
            $gprmc = new Gprmc($gprmc_sentence);
            if ($gprmc->isValid()) {
                // check if device exists
                // (dirty but will work for now)
                try {
                    Device::create($id);
                } catch (\Exception $exception) {
                    // probably already exists
                }
                Location::save($id, $gprmc);
            }
        }

        // set name
        if ($device_name) {
            // but first check if exists
            // (dirty but will work for now)
            try {
                Device::create($id);
            } catch (\Exception $exception) {
                // probably already exists
            }
            Device::setName($id, $device_name);
        }


    }
}