<?php

namespace Tests\Framework\Configuration
{

    use Framework;

    class ConfigurationTest extends \PHPUnit_Framework_TestCase{

        public function testObject(){

            $configuration = new Framework\Configuration(array(
                "type" => "ini"
            ));

            $this->assertInstanceOf('Framework\Configuration', $configuration);
        }
    }
}
