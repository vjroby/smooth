<?php

namespace Tests\Cache\Memcached
{
    use \Framework as Framework;
    use Tests\Test;

    class Memcached extends Test {

        public function __construct(){
            echo 'Tests Test Cache Memcached loaded.'.PHP_EOL;

            self::runAllMemcached();
        }

        public static function runAllMemcached(){

            $reflect =  new \ReflectionClass(get_class());
            $methods = $reflect->getMethods(\ReflectionMethod::IS_STATIC);
            $parentMethods = $reflect->getParentClass()->getMethods(\ReflectionMethod::IS_STATIC);
            $parentMethodsArray = [];
            foreach ($parentMethods as $parentMethod) {
                $parentMethodsArray[] = $parentMethod->getName();
            }
            $parentMethodsArray[] = __FUNCTION__;


            foreach ($methods as $method) {
                $name = $method->name;
                if(!in_array($name, $parentMethodsArray)){
                    self::$name();

                }
            }
        }
        public static function MemcachedInit(){
            Framework\Test::add(
                function()
                {
                    $cache = new Framework\Cache(array(
                        "type" => "memcached"
                    ));

                    $cache = $cache->initialize();
                    return ($cache instanceof Framework\Cache\Driver\Memcached);
                },
                "Cache\Driver\Memcached initializes",
                "Cache\Driver\Memcached"
            );
        }

        public static function MemcachedConnect(){
            Framework\Test::add(
                function()
                {
                    $cache = new Framework\Cache(array(
                        "type" => "memcached"
                    ));
                    $cache = $cache->initialize();
                    return ($cache->connect() instanceof Framework\Cache\Driver\Memcached);
                },
                "Cache\Driver\Memcached connects and returns self",
                "Cache\Driver\Memcached"
            );
        }

        public static function MemcachedDisconnect(){
            Framework\Test::add(
                function()
                {
                    $cache = new Framework\Cache(array(
                        "type" => "memcached"
                    ));

                    $cache = $cache->initialize();
                    $cache = $cache->connect();
                    $cache = $cache->disconnect();

                    try
                    {
                        $cache->get("anything");
                    }
                    catch (Framework\Cache\Exception\Service $e)
                    {
                        return ($cache instanceof Framework\Cache\Driver\Memcached);
                    }

                    return false;
                },
                "Cache\Driver\Memcached disconnects and returns self",
                "Cache\Driver\Memcached"
            );
        }

        public static function MemcachedSetValues(){

            Framework\Test::add(
                function()
                {
                    $cache = new Framework\Cache(array(
                        "type" => "memcached"
                    ));

                    $cache = $cache->initialize();
                    $cache = $cache->connect();

                    return ($cache->set("foo", "bar", 1) instanceof Framework\Cache\Driver\Memcached);
                },
                "Cache\Driver\Memcached sets values and returns self",
                "Cache\Driver\Memcached"
            );
        }

        public static function MemcachedRetrieveValues(){

            Framework\Test::add(
                function()
                {
                    $cache = new Framework\Cache(array(
                        "type" => "memcached"
                    ));

                    $cache = $cache->initialize();
                    $cache = $cache->connect();

                    return ($cache->get("foo") == "bar");
                },
                "Cache\Driver\Memcached retrieves values",
                "Cache\Driver\Memcached"
            );
        }

        public static function MemcachedDefaultValues(){

            Framework\Test::add(
                function()
                {
                    $cache = new Framework\Cache(array(
                        "type" => "memcached"
                    ));

                    $cache = $cache->initialize();
                    $cache = $cache->connect();

                    return ($cache->get("404", "baz") == "baz");
                },
                "Cache\Driver\Memcached returns default values",
                "Cache\Driver\Memcached"
            );
        }

        public static function MemcachedExpiryTimeOnValues(){
            Framework\Test::add(
                function()
                {
                    $cache = new Framework\Cache(array(
                        "type" => "memcached"
                    ));

                    $cache = $cache->initialize();
                    $cache = $cache->connect();

                    // we sleep to void the 1 second cache key/value above
                    sleep(1);

                    return ($cache->get("foo") == null);
                },
                "Cache\Driver\Memcached expires values",
                "Cache\Driver\Memcached"
            );
        }

        public static function MemcachedEraseValues(){
            Framework\Test::add(
                function()
                {
                    $cache = new Framework\Cache(array(
                        "type" => "memcached"
                    ));

                    $cache = $cache->initialize();
                    $cache = $cache->connect();

                    $cache = $cache->set("hello", "world");
                    $cache = $cache->erase("hello");

                    return ($cache->get("hello") == null && $cache instanceof Framework\Cache\Driver\Memcached);
                },
                "Cache\Driver\Memcached erases values and returns self",
                "Cache\Driver\Memcached"
            );
        }

    }
}
 