<?php

namespace TapItTest\Models;


class Gprmc
{


    //eg2. $GPRMC,225446,A,4916.45,N,12311.12,W,000.5,054.7,191194,020.3,E*68
    //eg3. $GPRMC,220516,A,5133.82,N,00042.24,W,173.8,231.8,130694,004.2,W*70
    //              1    2    3    4    5     6    7    8      9     10  11 12
    public $timeStamp; //           220516     Time Stamp  = UTC of position fix
    public $validity; //            A          validity - A-ok, V-invalid  = Data status (V=navigation receiver warning)
    public $latitude; //            5133.82    current Latitude  = Latitude of fix [51 deg 33.82 min]
    public $latitudeDirection; //   N          North/South  = N or S
    public $longitude; //           00042.24   current Longitude  = Longitude of fix [0 deg 42.24 min]
    public $longitudeDirection; //  W          East/West  = E or W
    public $speed; //               173.8      Speed in knots  = Speed over ground in knots
    public $course; //              231.8      True course  = Track made good in degrees True
    public $dateStamp; //                130694     Date Stamp  = UT date
    public $variation; //           004.2      Variation  = Magnetic variation degrees (Easterly var. subtracts from true course)
    public $variationDirection; //  W          East/West  = E or W
    public $checksum; //            *70        checksum  = Checksum

    public $calculatedChecksum;
    public $payload; // sentence without checksum

    public function __construct($sentence)
    {
        if (substr($sentence, 0, 6) != '$GPRMC') {
            throw new \Exception('not GPRMC');
        }
        $checkParts = explode('*', $sentence);
        $this->payload = substr($checkParts[0], 1);
        $this->checksum = $checkParts[1];

        $payloadParts = explode(',', $this->payload);
//print_r($payloadParts); exit;
        $this->timeStamp = $payloadParts[1];
        $this->validity = $payloadParts[2];
        $this->latitude = $payloadParts[3];
        $this->latitudeDirection = $payloadParts[4];
        $this->longitude = $payloadParts[5];
        $this->longitudeDirection = $payloadParts[6];
        $this->speed = $payloadParts[7];
        $this->course = $payloadParts[8];
        $this->dateStamp = $payloadParts[9];
        $this->variation = $payloadParts[10];
        $this->variationDirection = $payloadParts[11];
    }

    public function isValid()
    {
        return $this->validity == 'A';
    }

    public function getTime()
    {
        $hh = substr($this->timeStamp, 0, 2);
        $mm = substr($this->timeStamp, 2, 2);
        $ss = substr($this->timeStamp, 4, 2);
        return "{$hh}:{$mm}:{$ss}";
    }

    public function getDate()
    {
        $dd = substr($this->dateStamp, 0, 2);
        $mm = substr($this->dateStamp, 2, 2);
        $yy = substr($this->dateStamp, 4, 2);
        return date('Y-m-d', strtotime("20{$yy}-{$mm}-{$dd}"));
    }

    public function getLatLongString()
    {
        // again, no specification on GPRMC format so this is just a guessing
        // 5133.82 is 51 deg 33.82 min ???
        $latD = substr($this->latitude, 0, 2);
        $latM = substr($this->latitude, 2);
        // dd = d + m/60 + s/3600
        $lat = $latD + $latM/60;
        if ($this->latitudeDirection == 'S') {
            $lat = -1 * $lat;
        }

        // 00042.24 is 0 deg 42.24 min ???
        $longD = substr($this->longitude, 0, 3);
        $longM = substr($this->longitude, 3);
        // dd = d + m/60 + s/3600
        $long = $longD + $longM/60;
        if ($this->longitudeDirection == 'W') {
            $long = -1 * $long;
        }


        $latLong =implode(',', [$lat, $long]);

        return $latLong;
    }

    /**
     * this doesn't work for some reason
     *
     * maybe the test TCP data package is wrong because GPRMC
     * sentence ends with `D` before the checksum ???
     *
     * @return int
     */
    public function calculateChecksum()
    {
        $checksum = 0;
        $payloadLength = strlen($this->payload);
        for ($i = 0; $i < $payloadLength; $i++) {
            $ch = $this->payload[$i];
            $checksum ^= ord($ch);

//            $dbg = [
//                'i' => $i,
//                'ch' => $ch,
//                'ord' => ord($ch),
//                'hex' => dechex(ord($ch)),
//            ];
//
//            if ($i == 0 || $i == ($payloadLength-1)) {
//                print_r($dbg);
//            }

        }
//var_dump($checksum); exit;
        return $checksum;
    }

}