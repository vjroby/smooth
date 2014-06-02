<?php

namespace Tests\Model
{
    use Tests\Test as Test;
    use \Framework as Framework;
    use \Application\Model as Model;

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

        public static function inserts(){
            $database = Framework\Registry::get('database');

            Framework\Test::add(
                function() use ($database)
                {
                    $example = new Model\Example(array(
                        "name" => "foo",
                        "created" => date("Y-m-d H:i:s")
                    ));

                    return ($example->save() > 0);
                },
                "Model inserts rows",
                "Model"
            );
        }

        public static function fetchesNumberRows(){
            $database = Framework\Registry::get('database');

            Framework\Test::add(
                function() use ($database)
                {
                    return (Model\Example::count() == 1);
                },
                "Model fetches number of rows",
                "Model"
            );
        }

        public static function saveMultipleTimes(){
            $database = Framework\Registry::get('database');

            Framework\Test::add(
                function() use ($database)
                {
                    $example = new Model\Example(array(
                        "name" => "foo",
                        "created" => date("Y-m-d H:i:s")
                    ));

                    $example->save();
                    $example->save();
                    $example->save();

                    return (Model\Example::count() == 2);
                },
                "Model saves single row multiple times",
                "Model"
            );
        }

        public static function update(){
            $database = Framework\Registry::get('database');

            Framework\Test::add(
                function() use ($database)
                {
                    $example = new Model\Example(array(
                        "id" => 1,
                        "name" => "hello",
                        "created" => date("Y-m-d H:i:s")
                    ));
                    $example->save();

                    return (Model\Example::first()->name == "hello");
                },
                "Model updates rows",
                "Model"
            );
        }

        public static function delete(){
            $database = Framework\Registry::get('database');

            Framework\Test::add(
                function() use ($database)
                {
                    $example = new Model\Example(array(
                        "id" => 2
                    ));
                    $example->delete();

                    return (Model\Example::count() == 1);
                },
                "Model deletes rows",
                "Model"
            );
        }
    }
}
 