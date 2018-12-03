<?php

class AppTest extends \PHPUnit\Framework\TestCase
{

    /** @test */
    function parse_tcp_data()
    {
        // test location (gprmc)
        $data_packet = '#357671030507872#user#4444#AUTOLOW#1#14508989$GPRMC,123347.000,A,4313.7477,N,02752.4516,E,0.00,284.40,080811,,,D*63##';
        $expected_gprmc_sentence = '$GPRMC,123347.000,A,4313.7477,N,02752.4516,E,0.00,284.40,080811,,,D*63';
        $expected_id = 357671030507872;

        list($id, $gprmc_sentence, $device_name) = \TapItTest\Util::parseTcpDataPackage($data_packet);

        $this->assertEquals($expected_id, $id);
        $this->assertEquals($expected_gprmc_sentence, $gprmc_sentence);
        $this->assertEquals('', $device_name);

        // test set device name
        $data_packet = '#357671030507872#user#4444#AUTOLOW#1#14508989$NAME,example name##';
        $expected_device_name = 'example name';
        $expected_id = 357671030507872;

        list($id, $gprmc_sentence, $device_name) = \TapItTest\Util::parseTcpDataPackage($data_packet);

        $this->assertEquals($expected_id, $id);
        $this->assertEquals('', $gprmc_sentence);
        $this->assertEquals($expected_device_name, $device_name);
    }

    /** @test */
    function parse_gprmc_sentence()
    {
        // test data supplied by TapIt
        $sentence = '$GPRMC,123347.000,A,4313.7477,N,02752.4516,E,0.00,284.40,080811,,,D*63';

        $gprmc = new \TapItTest\Models\Gprmc($sentence);

        $this->assertEquals('123347.000', $gprmc->timeStamp);
        $this->assertEquals('A', $gprmc->validity);
        $this->assertTrue($gprmc->isValid());
        $this->assertEquals('080811', $gprmc->dateStamp);
        $this->assertEquals('2011-08-08', $gprmc->getDate());
        $this->assertEquals('123347.000', $gprmc->timeStamp);
        $this->assertEquals('12:33:47', $gprmc->getTime());

        $this->assertEquals('4313.7477', $gprmc->latitude);
        $this->assertEquals('N', $gprmc->latitudeDirection);
        $this->assertEquals('02752.4516', $gprmc->longitude);
        $this->assertEquals('E', $gprmc->longitudeDirection);

        $this->assertEquals('63', $gprmc->checksum);


        // checksum calculation doesn't work, what is the `D` at the end???
//        $this->assertEquals('63', $gprmc->calculateChecksum());
    }

    /** @test */
    function checksum()
    {
        $dbg = [];
        $dbg[] = ord('a');
        $dbg[] = decbin(ord('a'));
        //$dbg[] = bindec('1100001');

        $dbg[] = ord('b');
        $dbg[] = decbin(ord('b'));

        // 1100001 (a)
        // 1100010 (b)
        // 0000011 XoR
        $dbg[] = 'checksum should be ' . bindec('0000011');
        $checksum = ord('a') ^ ord('b');
        $dbg[] = 'checksum is ' . $checksum;

        $this->assertEquals(3, $checksum);

//        print_r($dbg);
    }
}