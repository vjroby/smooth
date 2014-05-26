<?php

namespace Tests\Configuration
{
    use \Tests\Test as Test;
    use \Framework as Framework;

    class TestConfiguration extends Test{


        public function __construct(){
            echo 'Test configuration loaded.'.PHP_EOL;

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

        public static function configuration(){
            Framework\Test::add(
                function()
                {
                    $configuration = new Framework\Configuration();
                    return ($configuration instanceof Framework\Configuration);
                },
                "Configuration instantiates in uninitialized state",
                "Configuration"
            );
        }
    }
}
 