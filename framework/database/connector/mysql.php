<?php
namespace Framework\Database\Connector
{
    use Framework\Database as Database;
    use Framework\Database\Exception as Exception;

    class Mysql extends Database\Connector{

        protected $_service;

        protected $_statement;

        /**
         * @readwrite
         */
        protected $_host;

        /**
         * @readwrite
         */
        protected $_username;

        /**
         * @readwrite
         */
        protected $_password;

        /**
         * @readwrite
         */
        protected $_schema;

        /**
         * @readwrite
         */
        protected $_port = "3306";

        /**
         * @readwrite
         */
        protected $_charset = "utf8";

        /**
         * @readwrite
         */
        protected $_engine = "InnoDB";

        /**
         * @readwrite
         */
        protected $_isConnected = false;

        // checks if connected to the database
        protected function _isValidService()
        {
            $isEmpty = empty($this->_service);
            $isInstance = $this->_service instanceof \PDO;

            if ($this->isConnected && $isInstance && !$isEmpty)
            {
                return true;
            }

            return false;
        }

        // connects to the database
        public function connect()
        {
            if (!$this->_isValidService())
            {
                try{
                    //PDO('mysql:host=localhost;dbname=test', $user, $pass);
                    $this->_service = new \PDO('mysql:host='.$this->_host.';dbname='.$this->_schema.';port='.$this->_port,
                        $this->_username,
                        $this->_password,
                        array(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION)
                    );
                }catch (\PDOException $e){
                    throw new Exception\Service("Unable to connect to service. Message: ".$e->getMessage());
                }

                $this->isConnected = true;
            }

            return $this;
        }

        // disconnects from the database
        public function disconnect()
        {
            if ($this->_isValidService())
            {
                $this->isConnected = false;
                $this->_service->close();
            }

            return $this;
        }

        // returns a corresponding query instance
        public function query()
        {
            return new Database\Query\Mysql(array(
                "connector" => $this
            ));
        }

        // executes the provided SQL statement
        public function execute($sql)
        {
            if (!$this->_isValidService())
            {
                throw new Exception\Service("Not connected to a valid service");
            }
            $this->_statement = $this->_service->prepare($sql);
            $this->_statement->execute();
            return $this->_statement;
        }

        // escapes the provided value to make it safe for queries
        /**
         * @param $value
         * @return mixed
         * @throws \Framework\Database\Exception\Service
         */
        public function escape($value)
        {
            if (!$this->_isValidService())
            {
                throw new Exception\Service("Not connected to a valid service");
            }

            return $this->_statement->prepare($value);
        }

        // returns the ID of the last row
        // to be inserted
        public function getLastInsertId()
        {
            if (!$this->_isValidService())
            {
                throw new Exception\Service("Not connected to a valid service");
            }

            return $this->_statement->lastInsertId();
        }

        // returns the number of rows affected
        // by the last SQL query executed
        public function getAffectedRows()
        {
            if (!$this->_isValidService())
            {
                throw new Exception\Service("Not connected to a valid service");
            }

            return $this->_statement->rowCount();
        }

        // returns the last error of occur
        public function getLastError()
        {
            if (!$this->_isValidService())
            {
                throw new Exception\Service("Not connected to a valid service");
            }
            $error = $this->_service->errorInfo();
            return $error[0].':'.$error[1].' - '.$error[2];
        }

        public function sync($model)
        {
            $lines = array();
            $indices = array();
            $columns = $model->columns;
            $template = "CREATE TABLE `%s` (\n%s,\n%s\n) ENGINE=%s DEFAULT CHARSET=%s;";

            foreach ($columns as $column)
            {
                $raw = $column["raw"];
                $name = $column["name"];
                $type = $column["type"];
                $length = $column["length"];

                if ($column["primary"])
                {
                    $indices[] = "PRIMARY KEY (`{$name}`)";
                }
                if ($column["index"])
                {
                    $indices[] = "KEY `{$name}` (`{$name}`)";
                }

                switch ($type)
                {
                    case "autonumber":
                    {
                        $lines[] = "`{$name}` int(11) NOT NULL AUTO_INCREMENT";
                        break;
                    }
                    case "text":
                    {
                        if ($length !== null && $length <= 255)
                        {
                            $lines[] = "`{$name}` varchar({$length}) DEFAULT NULL";
                        }
                        else
                        {
                            $lines[] = "`{$name}` text";
                        }
                        break;
                    }
                    case "integer":
                    {
                        $lines[] = "`{$name}` int(11) DEFAULT NULL";
                        break;
                    }
                    case "decimal":
                    {
                        $lines[] = "`{$name}` float DEFAULT NULL";
                        break;
                    }
                    case "boolean":
                    {
                        $lines[] = "`{$name}` tinyint(4) DEFAULT NULL";
                        break;
                    }
                    case "datetime":
                    {
                        $lines[] = "`{$name}` datetime DEFAULT NULL";
                        break;
                    }
                }
            }

            $table = $model->table;
            $sql = sprintf(
                $template,
                $table,
                join(",\n", $lines),
                join(",\n", $indices),
                $this->_engine,
                $this->_charset
            );

            $result = $this->execute("DROP TABLE IF EXISTS {$table};");
            if ($result === false)
            {
                $error = $this->lastError;
                throw new Exception\Sql("There was an error in the query: {$error}");
            }

            $result = $this->execute($sql);
            if ($result === false)
            {
                $error = $this->lastError;
                throw new Exception\Sql("There was an error in the query: {$error}");
            }

            return $this;
        }
    }
}
 