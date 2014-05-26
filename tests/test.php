<?php
namespace Tests{


    class Test extends \Framework\Test{
        public function __construct(){
            echo 'Tests class loaded'.PHP_EOL;
//            new Cache\TestCache();
//            new Cache\Memcached\Memcached();
            new Configuration\TestConfiguration();
            new Configuration\Ini\Ini();

        }
    }
}