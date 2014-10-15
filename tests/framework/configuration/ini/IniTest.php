<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 15.10.2014
 * Time: 08:08
 */

namespace Tests\Framework\Configuration\Ini
{
    use Framework;

    class IniTest extends \PHPUnit_Framework_TestCase{

        public function testInstance(){

            $iniConfiguration = new Framework\Configuration\Driver\Ini(array());

            $this->assertInstanceOf('Framework\Configuration\Driver\Ini', $iniConfiguration);
        }
    }
}

