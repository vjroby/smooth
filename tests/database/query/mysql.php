<?php

namespace Tests\Database\Query
{
    use \Framework as Framework;
    use Tests\Database\TestDatabase as TestDatabase;

    class Mysql extends TestDatabase{

        static $options = array(
            "type" => "mysql",
            "options" => array(
                "host" => "localhost",
                "username" => "root",
                "password" => "root",
                "schema" => "prophpmvc"
            )
        );

        public function __construct(){
            echo 'Tests Query Mysql loaded.'.PHP_EOL;

            self::runAllMysql();
        }

        public static function runAllMysql(){
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

        public static function returnsQuery(){
            $options = self::$options;

            Framework\Test::add(
                function() use ($options)
                {
                    $database = new Framework\Database($options);
                    $database = $database->initialize();
                    $database = $database->connect();
                    $query = $database->query();

                    return ($query instanceof Framework\Database\Query\Mysql);
                },
                "Database\Connector\Mysql returns instance of Database\Query\Mysql",
                "Database\Query\Mysql"
            );
        }

        public static function referencesConnector(){
            $options = self::$options;

            Framework\Test::add(
                function() use ($options)
                {
                    $database = new Framework\Database($options);
                    $database = $database->initialize();
                    $database = $database->connect();
                    $query = $database->query();

                    return ($query->connector instanceof Framework\Database\Connector\Mysql);
                },
                "Database\Query\Mysql references connector",
                "Database\Query\Mysql"
            );
        }

        public static function fetchFirstRow(){
            $options = self::$options;

            Framework\Test::add(
                function() use ($options)
                {
                    $database = new Framework\Database($options);
                    $database = $database->initialize();
                    $database = $database->connect();

                    $row = $database->query()
                        ->from("tests")
                        ->first();

                    return ($row["id"] == 1);
                },
                "Database\Query\Mysql fetches first row",
                "Database\Query\Mysql"
            );
        }

        public static function fetchesMultipleRow(){
            $options = self::$options;

            Framework\Test::add(
                function() use ($options)
                {
                    $database = new Framework\Database($options);
                    $database = $database->initialize();
                    $database = $database->connect();

                    $rows = $database->query()
                        ->from("tests")
                        ->all();

                    return (sizeof($rows) == 4);
                },
                "Database\Query\Mysql fetches multiple rows",
                "Database\Query\Mysql"
            );
        }

        public static function fetchesNumberRows(){
            $options = self::$options;

            Framework\Test::add(
                function() use ($options)
                {
                    $database = new Framework\Database($options);
                    $database = $database->initialize();
                    $database = $database->connect();

                    $count = $database
                        ->query()
                        ->from("tests")
                        ->count();

                    return ($count == 4);
                },
                "Database\Query\Mysql fetches number of rows",
                "Database\Query\Mysql"
            );
        }

        public static function acceptsLimit(){
            $options = self::$options;

            Framework\Test::add(
                function() use ($options)
                {
                    $database = new Framework\Database($options);
                    $database = $database->initialize();
                    $database = $database->connect();

                    $rows = $database->query()
                        ->from("tests")
                        ->limit(1, 2)
                        ->order("id", "desc")
                        ->all();

                    return (sizeof($rows) == 1 && $rows[0]["id"] == 3);
                },
                "Database\Query\Mysql accepts LIMIT, OFFSET, 
            ORDER and DIRECTION clauses",
                "Database\Query\Mysql"
            );
        }

        public static function acceptsWhere(){
            $options = self::$options;

            Framework\Test::add(
                function() use ($options)
                {
                    $database = new Framework\Database($options);
                    $database = $database->initialize();
                    $database = $database->connect();

                    $rows = $database->query()
                        ->from("tests")
                        ->where("id != ?", 1)
                        ->where("id != ?", 3)
                        ->where("id != ?", 4)
                        ->all();

                    return (sizeof($rows) == 1 && $rows[0]["id"] == 2);
                },
                "Database\Query\Mysql accepts WHERE clauses",
                "Database\Query\Mysql"
            );
        }

        public static function acceptsAlias(){
            $options = self::$options;

            Framework\Test::add(
                function() use ($options)
                {
                    $database = new Framework\Database($options);
                    $database = $database->initialize();
                    $database = $database->connect();

                    $rows = $database->query()
                        ->from("tests", array(
                            "id" => "foo"
                        ))
                        ->all();

                    return (sizeof($rows) && isset($rows[0]["foo"]) && $rows[0]["foo"] == 1);
                },
                "Database\Query\Mysql can alias fields",
                "Database\Query\Mysql"
            );
        }

        public static function joinWithAlias(){
            $options = self::$options;

            Framework\Test::add(
                function() use ($options)
                {
                    $database = new Framework\Database($options);
                    $database = $database->initialize();
                    $database = $database->connect();

                    $rows = $database->query()
                        ->from("tests", array(
                            "tests.id" => "foo"
                        ))
                        ->join("tests AS baz", "tests.id = baz.id", array(
                            "baz.id" => "bar"
                        ))
                        ->all();

                    return (sizeof($rows) && $rows[0]['foo'] == $rows[0]['bar']);
                },
                "Database\Query\Mysql can join tables and alias joined fields",
                "Database\Query\Mysql"
            );
        }

        public static function InsertRows(){
            $options = self::$options;

            Framework\Test::add(
                function() use ($options)
                {
                    $database = new Framework\Database($options);
                    $database = $database->initialize();
                    $database = $database->connect();

                    $result = $database->query()
                        ->from("tests")
                        ->save(array(
                            "number" => 3,
                            "text" => "foo",
                            "boolean" => true
                        ));

                    return ($result == 5);
                },
                "Database\Query\Mysql can insert rows",
                "Database\Query\Mysql"
            );
        }

        public static function updateRows(){
            $options = self::$options;

            Framework\Test::add(
                function() use ($options)
                {
                    $database = new Framework\Database($options);
                    $database = $database->initialize();
                    $database = $database->connect();

                    $result = $database->query()
                        ->from("tests")
                        ->where("id = ?", 5)
                        ->save(array(
                            "number" => 3,
                            "text" => "foo",
                            "boolean" => false
                        ));

                    return ($result == 0);
                },
                "Database\Query\Mysql can update rows",
                "Database\Query\Mysql"
            );
        }

        public static function deleteRows(){
            $options = self::$options;

            Framework\Test::add(
                function() use ($options)
                {
                    $database = new Framework\Database($options);
                    $database = $database->initialize();
                    $database = $database->connect();

                    $database->query()
                        ->from("tests")
                        ->delete();

                    return ($database->query()->from("tests")->count() == 0);
                },
                "Database\Query\Mysql can delete rows",
                "Database\Query\Mysql"
            );
        }

    }
}
 