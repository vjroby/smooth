<?php
namespace Framework\Database
{
    use Framework\Base as Base;
    use Framework\Utility\ArrayMethods as ArrayMethods;
    use Framework\Database\Exception as Exception;

    class Query extends Base{

        /**
         * @readwrite
         */
        protected $_connector;

        /**
         * @read
         */
        protected $_from;

        /**
         * @read
         */
        protected $_fields;

        /**
         * @read
         */
        protected $_limit;

        /**
         * @read
         */
        protected $_offset;

        /**
         * @read
         */
        protected $_order;

        /**
         * @read
         */
        protected $_direction;

        /**
         * @read
         */
        protected $_join = array();

        /**
         * @read
         */
        protected $_where = array();
        /**
         * @readwrite
         */
        protected  $_type;

        protected function _getExceptionForImplementation($method)
        {
            return new Exception\Implementation("{$method} method not implemented");
        }

        protected function _quote($value)
        {
            if (is_string($value))
            {
                // no used in PDO must use prepare $escaped = $this->connector->escape($value);
                return "'{$value}'";
            }

            if (is_array($value))
            {
                $buffer = array();

                foreach ($value as $i)
                {
                    array_push($buffer, $this->_quote($i));
                }

                $buffer = join(", ", $buffer);
                return "({$buffer})";
            }

            if (is_null($value))
            {
                return "NULL";
            }

            if (is_bool($value))
            {
                return (int) $value;
            }

            return $value;
        }

        protected function _buildSelect()
        {
            $fields = array();
            $where = $order = $limit = $join = "";
            $template = "SELECT %s FROM %s %s %s %s %s";

            foreach ($this->fields as $table => $_fields)
            {
                foreach ($_fields as $field => $alias)
                {
                    if (is_string($field))
                    {
                        $fields[] = "{$field} AS {$alias}";
                    }
                    else
                    {
                        $fields[] = $alias;
                    }
                }
            }

            $fields = join(", ", $fields);

            $_join = $this->join;
            if (!empty($_join))
            {
                $join = join(" ", $_join);
            }

            $_where = $this->where;
            if (!empty($_where))
            {
                // TODO bind_params
                $joined = join(" AND ", $_where);
                $where = "WHERE {$joined}";
            }

            $_order = $this->order;
            if (!empty($_order))
            {
                $_direction = $this->direction;
                $order = "ORDER BY {$_order} {$_direction}";
            }

            $_limit = $this->limit;
            if (!empty($_limit))
            {
                $_offset = $this->offset;

                if ($_offset)
                {
                    $limit = "LIMIT {$_limit}, {$_offset}";
                }
                else
                {
                    $limit = "LIMIT {$_limit}";
                }
            }

            return sprintf($template, $fields, $this->from, $join, $where, $order, $limit);
        }

        protected function _buildInsert($data)
        {
            $fields = array();
            $values = array();
            $template = "INSERT INTO %s (%s) VALUES (%s)";
            // TODO bind_params
            foreach ($data as $field => $value)
            {
                $fields[] = $field;
                $values[] = ':'.$field;
            }

            $values = join(", ", $values);
            $fields = join(", ", $fields);


            return sprintf($template, $this->from, $fields, $values);
        }

        protected function _buildUpdate($data)
        {
            $parts = array();
            $where = $limit = "";
            $template = "UPDATE %s SET %s %s %s";
            // TODO bind_params
            foreach ($data as $field => $value)
            {
                $parts[] = "{$field} = ".$this->_quote($value);
            }

            $parts = join(", ", $parts);

            $_where = $this->where;
            if (!empty($_where))
            {
                $joined = join(", ", $_where);
                $where = "WHERE {$joined}";
            }

            $_limit = $this->limit;
            if (!empty($_limit))
            {
                $_offset = $this->offset;
                $limit = "LIMIT {$_limit} {$_offset}";
            }

            return sprintf($template, $this->from, $parts, $where, $limit);
        }

        protected function _buildDelete()
        {
            $where = $limit ="";
            $template = "DELETE FROM %s %s %s";

            $_where = $this->where;
            if (!empty($_where))
            {
                // TODO bind_params
                $joined = join(", ", $_where);
                $where = "WHERE {$joined}";
            }

            $_limit = $this->limit;
            if (!empty($_limit))
            {
                $_offset = $this->offset;
                $limit = "LIMIT {$_limit} {$_offset}";
            }

            return sprintf($template, $this->from, $where, $limit);
        }

        public function save($data)
        {
            $isInsert = sizeof($this->_where) == 0;

            if ($isInsert)
            {
                $sql = $this->_buildInsert($data);
            }
            else
            {
                $sql = $this->_buildUpdate($data);
            }
            $this->_connector->_statement = $this->_connector->prepare($sql);
            $this->bind_params($data);


            $result = $this->_connector->execute($sql);

            if ($result === false)
            {
                throw new Exception\Sql();
            }

            if ($isInsert)
            {

                return $this->_connector->lastInsertId;
            }

            return 0;
        }

        public function delete()
        {
            $sql = $this->_buildDelete();
            $result = $this->_connector->execute($sql);

            if ($result === false)
            {
                throw new Exception\Sql();
            }

            return $this->_connector->affectedRows;
        }

        public function from($from, $fields = array("*"))
        {
            if (empty($from))
            {
                throw new Exception\Argument("Invalid argument");
            }

            $this->_from = $from;

            if ($fields)
            {
                $this->_fields[$from] = $fields;
            }

            return $this;
        }

        public function join($join, $on, $fields = array())
        {
            if (empty($join))
            {
                throw new Exception\Argument("Invalid argument");
            }

            if (empty($on))
            {
                throw new Exception\Argument("Invalid argument");
            }

            $this->_fields += array($join => $fields);
            $this->_join[] = "JOIN {$join} ON {$on}";

            return $this;
        }

        public function limit($limit, $page = 1)
        {
            if (empty($limit))
            {
                throw new Exception\Argument("Invalid argument");
            }

            $this->_limit = $limit;
            $this->_offset = $limit * ($page - 1);

            return $this;
        }

        public function order($order, $direction = "asc")
        {
            if (empty($order))
            {
                throw new Exception\Argument("Invalid argument");
            }

            $this->_order = $order;
            $this->_direction = $direction;

            return $this;
        }

        public function where()
        {
            $arguments = func_get_args();

            if (sizeof($arguments) < 1)
            {
                throw new Exception\Argument("Invalid argument");
            }

            $arguments[0] = preg_replace("#\?#", "%s", $arguments[0]);

            foreach (array_slice($arguments, 1, null, true) as $i => $parameter)
            {
                $arguments[$i] = $this->_quote($arguments[$i]);
            }

            $this->_where[] = call_user_func_array("sprintf", $arguments);

            return $this;
        }

        public function first()
        {
            $limit = $this->_limit;
            $offset = $this->_offset;

            $this->limit(1);

            $all = $this->all();
            $first = ArrayMethods::first($all);

            if ($limit)
            {
                $this->_limit = $limit;
            }
            if ($offset)
            {
                $this->_offset = $offset;
            }

            return $first;
        }

        public function count()
        {
            $limit = $this->limit;
            $offset = $this->offset;
            $fields = $this->fields;

            $this->_fields = array($this->from => array("COUNT(1)" => "rows"));

            $this->limit(1);
            $row = $this->first();

            $this->_fields = $fields;

            if ($fields)
            {
                $this->_fields = $fields;
            }
            if ($limit)
            {
                $this->_limit = $limit;
            }
            if ($offset)
            {
                $this->_offset = $offset;
            }

            return $row["rows"];
        }

        public function bind_params($params = array()){
            $count = 'A';
            $param_name = 'param';
            if(!empty($params)) {

                foreach ($params as $key => $value) {
                    switch (gettype($value)){
                        case 'integer':
                            $type = \PDO::PARAM_INT;
                            $value = (integer)$value;
                            break;
                        case 'string':
                            $type = \PDO::PARAM_STR;
                            $value = (string)$value;
                            break;
                        case 'boolean':
                            $type = \PDO::PARAM_BOOL;
                            $value = (boolean)$value;
                            break;
                        case 'double':
                            $type = \PDO::PARAM_STR;
                            $value = (string)$value;
                            break;
                        case 'blob':
                            $type = \PDO::PARAM_LOB;
                            $value = (string)$value;
                            break;
                        case 'NULL':
                            $type = \PDO::PARAM_NULL;
                            break;
                        default:
                            NULL;
                            break;
                    }
                    if (is_int($key)){
                        $key = $param_name.$count;
                        $count++;
                    }
                    $this->_connector->statement->bindValue($key, $value, $type);

                }
            }
        }
    }
}
 