<?php

namespace Tests\Model
{
    use Tests\Test as Test;
    use \Framework as Framework;
    use \App\Model as Model;

    class TestModel {
        public function __construct(){
            echo 'Test model loaded.'.PHP_EOL;

            $database = new Framework\Database(array(
                "type" => "mysql",
                "options" => array(
                    "host" => "localhost",
                    "username" => "root",
                    "password" => "root",
                    "schema" => "prophpmvc"
                )
            ));
            $database = $database->initialize();
            $database = $database->connect();

            Framework\Registry::set("database", $database);

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

        public static function sync(){
            $database = Framework\Registry::get("database");
            Framework\Test::add(
                function() use ($database)
                {
                    $example = new Model\Example();
                    return ($database->sync($example) instanceof Framework\Database\Connector\Mysql);
                },
                "Model syncs",
                "Model"
            );
        }
    }
}
 