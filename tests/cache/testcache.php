<?php

namespace Tests\Cache
{
    use \Tests\Test as Test;
    use \Framework as Framework;

    class TestCache extends Test {

        public function __construct(){
            echo 'Tests Test Cache loaded.'.PHP_EOL;

            self::runAll();

        }

        public static function runAll(){
//
//            self::cache();
//            self::memcachedInit();


            $reflect =  new \ReflectionClass(get_class());
            $methods = $reflect->getMethods(\ReflectionMethod::IS_STATIC);

            foreach ($methods as $method) {
                $name = $method->name;
                if(!in_array($name, array('run','add','start','runAll'))){
                    self::$name();

                }
            }
        }

        /**
         * 
         */
        public static function cache(){
            parent::add(
                function()
                {
                    $cache = new Framework\Cache();
                    return ($cache instanceof Framework\Cache);
                },
                "Cache instantiates in uninitialized state",
                "Cache"
            );
        }



    }
}
 