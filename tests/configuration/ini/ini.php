<?php

namespace Tests\Configuration\Ini
{
    use \Framework as Framework;
    use Tests\Configuration\TestConfiguration as Configuration;

    class Ini extends Configuration{

        public function __construct(){
            echo 'Tests Ini loaded.'.PHP_EOL;

            self::runAllIni();
        }

        public static function runAllIni(){
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

        public static function initializeIni(){
            Framework\Test::add(
                function()
                {
                    $configuration = new Framework\Configuration(array(
                        "type" => "ini"
                    ));
                    $configuration = $configuration->initialize();
                    return ($configuration instanceof Framework\Configuration\Driver\Ini);
                },
                "Configuration\Driver\Ini initializes",
                "Configuration\Driver\Ini"
            );
        }

        public static function parseIni(){
            Framework\Test::add(
                function()
                {
                    $configuration = new Framework\Configuration(array(
                        "type" => "ini"
                    ));

                    $configuration = $configuration->initialize();
                    $parsed = $configuration->parse("app/configuration/smooth");

                    return ($parsed->config->first == "hello" && $parsed->config->second->second == "bar");
                },
                "Configuration\Driver\Ini parses configuration files",
                "Configuration\Driver\Ini"
            );
        }

    }
}
 