<?php

namespace Tests\Database\Connector
{
    use \Framework as Framework;
    use Tests\Database\TestDatabase as TestDatabase;

    class Mysql extends TestDatabase {

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
            echo 'Tests Connector Mysql loaded.'.PHP_EOL;

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

        public static function mysqlInitializes(){
            $options = self::$options;

            Framework\Test::add(
                function() use ($options)
                {
                    $database = new Framework\Database($options);
                    $database = $database->initialize();

                    return ($database instanceof Framework\Database\Connector\Mysql);
                },
                "Database\Connector\Mysql initializes",
                "Database\Connector\Mysql"
            );
        }

        public static function mysqlConnects(){
            $options = self::$options;

            Framework\Test::add(
                function() use ($options)
                {
                    $database = new Framework\Database($options);
                    $database = $database->initialize();
                    $database = $database->connect();

                    return ($database instanceof Framework\Database\Connector\Mysql);
                },
                "Database\Connector\Mysql connects and returns self",
                "Database\Connector\Mysql"
            );
        }

        public static function mysqlDisconnects(){
            $options = self::$options;


            Framework\Test::add(
                function() use ($options)
                {
                    $database = new Framework\Database($options);
                    $database = $database->initialize();
                    $database = $database->connect();
                    $database = $database->disconnect();

                    try
                    {
                        $database->execute("SELECT 1");
                    }
                    catch (Framework\Database\Exception\Service $e)
                    {
                        return ($database instanceof Framework\Database\Connector\Mysql);
                    }

                    return false;
                },
                "Database\Connector\Mysql disconnects and returns self",
                "Database\Connector\Mysql"
            );
        }

        public static function mysqlEscapes(){
            $options = self::$options;

            Framework\Test::add(
                function() use ($options)
                {
                    $database = new Framework\Database($options);
                    $database = $database->initialize();
                    $database = $database->connect();

                    return (!($database->escape("foo'".'bar"') instanceof Framework\Database\Exception\Service));
                },
                "Database\Connector\Mysql escapes values",
                "Database\Connector\Mysql"
            );
        }

        public static function mysqlReturnLastError(){
            $options = self::$options;
            Framework\Test::add(
                function() use ($options)
                {
                    $database = new Framework\Database($options);
                    $database = $database->initialize();
                    $database = $database->connect();
                    $database->execute("SOME INVALID SQL");

                    return (bool) $database->lastError;
                },
                "Database\Connector\Mysql returns last error",
                "Database\Connector\Mysql"
            );
        }

        public static function mysqlExecutesQueries(){
            $options = self::$options;
            Framework\Test::add(
                function() use ($options)
                {
                    $database = new Framework\Database($options);
                    $database = $database->initialize();
                    $database = $database->connect();

                    $database->execute("
                        DROP TABLE IF EXISTS `tests`;
                    ");
                    $database->execute("
                        CREATE TABLE `tests` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `number` int(11) NOT NULL,
                            `text` varchar(255) NOT NULL,
                            `boolean` tinyint(4) NOT NULL,
                            PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                    ");

                    return !$database->lastError;
                },
                "Database\Connector\Mysql executes queries",
                "Database\Connector\Mysql"
            );
        }

        public static function lastInsertId(){
            $options = self::$options;

            Framework\Test::add(
                function() use ($options)
                {
                    $database = new Framework\Database($options);
                    $database = $database->initialize();
                    $database = $database->connect();

                    for ($i = 0; $i < 4; $i++)
                    {
                        $database->execute("
                            INSERT INTO `tests` (`number`, `text`, `boolean`) VALUES ('1337', 'text', '0');
                        ");
                    }

                    return $database->lastInsertId;
                },
                "Database\Connector\Mysql returns last inserted ID",
                "Database\Connector\Mysql"
            );
        }

        public static function affectedRows(){
            $options = self::$options;


            Framework\Test::add(
                function() use ($options)
                {
                    $database = new Framework\Database($options);
                    $database = $database->initialize();
                    $database = $database->connect();

                    $database->execute("
                        UPDATE `tests` SET `number` = 1338;
                    ");

                    return $database->affectedRows;
                },
                "Database\Connector\Mysql returns affected rows",
                "Database\Connector\Mysql"
            );
        }
    }
}
 