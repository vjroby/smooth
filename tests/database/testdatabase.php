<?php

namespace Tests\Database
{
    use Tests\Test as Test;
    use \Framework as Framework;

    class TestDatabase extends Test{

        public function __construct(){
            echo 'Test database loaded.'.PHP_EOL;

            self::runAll();

        }

        public static function runAll(){

            $reflect =  new \ReflectionClass(get_class());
            $methods = $reflect->getMethods(\ReflectionMethod::IS_STATIC);

            foreach ($methods as $method) {
                $name = $method->name;
                if(!in_array($name, array('run','add','start','runAll'))){
                    self::$name();

                }
            }
        }

        public static function databaseInstantiation(){
            Framework\Test::add(
                function()
                {
                    $database = new Framework\Database();
                    return ($database instanceof Framework\Database);
                },
                "Database instantiates in uninitialized state",
                "Database"
            );
        }

    }
}
 