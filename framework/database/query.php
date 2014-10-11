<?php
namespace Framework\Database
{
    use Framework\Base as Base;
    use Framework\Database;
    use Framework\Utility\ArrayMethods as ArrayMethods;
    use Framework\Database\Exception as Exception;

    class Query extends Base{

        /**
         * @readwrite
         */
        protected $_connector;

        /**
         * @readwrite
         */
        protected $_statement;

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
        protected $_group;

        /**
         * @read
         */
        protected $_direction;

        /**
         * @readwrite
         */
        public  $_params = array();

        /**
         * @read
         */
        protected $_join = array();

        /**
         * @read
         */
        protected $_where ;
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
            $where = $order = $limit = $join = $group = "";
            $template = "SELECT %s FROM %s %s %s %s %s %s";

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


                $where = " WHERE " . $_where;
            }

            $_order = $this->order;
            if (!empty($_order))
            {
                $_direction = $this->direction;
                $order = " ORDER BY {$_order} {$_direction} ";
            }
            $_group = $this->group;
            if (!empty($_group))
            {
                $group = " GROUP BY {$_group} ";
            }

            $_limit = $this->limit;
            if (!empty($_limit))
            {
                $_offset = $this->offset;

                if ($_offset)
                {
                    $limit = "LIMIT {$_offset}, {$_limit}";
                }
                else
                {
                    $limit = "LIMIT {$_limit}";
                }
            }

            return sprintf($template, $fields, $this->from, $join, $where, $group, $order, $limit);
        }

        protected function _buildInsert($data)
        {
            $fields = array();
            $values = array();
            $template = "INSERT INTO %s (%s) VALUES (%s)";
            // TODO bind_params
            foreach ($data as $field => $value)
            {
                $this->_params[":".$field] = $value;
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

            foreach ($data as $field => $value)
            {
                $this->_params[":".$field] = $value;
                $parts[] = "{$field} = :".$field;
            }

            $parts = join(", ", $parts);

            $_where = $this->where;
            if (!empty($_where))
            {

                $where = "WHERE {$_where}";
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
                //$joined = join(", ", $_where);
                $where = "WHERE {$_where}";
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
            $this->_statement = $this->_connector->service->prepare($sql);
            $this->_connector->bind_params($this->_statement,$this->_params);


            $result = $this->_statement->execute();

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

        public function join($join, $on, $fields = array(), $type = Database::LEFT_JOIN)
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
            $join_type = '';

            switch($type){
                case Database::LEFT_JOIN:
                    $join_type = ' LEFT ';
                    break;

                case Database::RIGHT_JOIN:
                    $join_type = ' RIGHT ';
                    break;

                case Database::OUTER_JOIN:
                    $join_type = ' OUTER ';
                    break;
            }
            $this->_join[] = $join_type." JOIN {$join} ON {$on}";

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

        /**
         * Add a where clause to the query.
         * @param string $field The column to wich this clause applies.
         * @param mixed $value [optional]
         * <p>The value to compare the column with.</p>
         * <p>Leave this parameter and the operator parameter blank (NULL) to have the "IS NULL" operator (WHERE column IS NULL).</p>
         * <p>If this parameter is an array and no operator is given the "IN" operator will be used (WHERE column IN (value1, value2).</p>
         * <p>If this parameter is an array you can also specify the "NOT IN" operator as the next parameter (WHERE column NOT IN (value1, value2).</p>
         * <p>If this parameter is a string and no operator is given the "=" operator will be used (WHERE column = value).</p>
         * @param string $operator [optional] <p>The operator that will be used (e.g. "IS NULL", "IN", "NOT IN", "=", "<>", ...).</p>
         * @param string $concatenator [optional] <p>Leave empty if this is the first where clause of the query.</p>
         * <p>Possible values "AND" and "OR". You can also use the andwhere and orwhere functions of this class.</p>
         * @return db
         */
        public function where($field, $value = NULL, $operator = NULL, $concatenator = NULL){
            if(empty($concatenator)){
                if (!empty($this->_where)){
                    $concatenator = " AND ";
                }else{
                    $concatenator = " ";

                }
            }
            $this->_where .= $this->condition(trim($field), $value, $operator, $concatenator);
            return $this;
        }
//        public function where()
//        {
//            $arguments = func_get_args();
//
//            if (sizeof($arguments) < 1)
//            {
//                throw new Exception\Argument("Invalid argument");
//            }
//            $a = $arguments[0];
//            if ($arguments[0] !== 0){
//                $arguments[0] = preg_replace("#\?#", "%s", $arguments[0]);
//            }elseif(isset($arguments[1])){
//                $arguments[0] = $arguments[1];
//            }
//
//            foreach (array_slice($arguments, 1, null, true) as $i => $parameter)
//            {
//                $arguments[$i] = $this->_quote($arguments[$i]);
//            }
//
//            $this->_where[] = call_user_func_array("sprintf", $arguments);
//
//            return $this;
//        }


        public function group($item){
            if (is_array($item)){
                $this->_group = implode(',', $item);
            }else{
                $this->_group = $item;
            }
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


    }
}
 