<?php

namespace Tests\Framework\Configuration{

    use Framework;

    class DriverTest extends \PHPUnit_Framework_TestCase{

        public function testInstance(){

            $driverConfiguration = new Framework\Configuration\Driver();

            $this->assertInstanceOf('Framework\Configuration\Driver', $driverConfiguration);
        }
    }
}


