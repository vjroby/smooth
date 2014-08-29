<?php

namespace Framework
{
    //use Framework\Base as Base;
    //use Framework\Registry as Registry;
    use Framework\Utility\Inspector as Inspector;
    use Framework\Utility\StringMethods as StringMethods;
    use Framework\Model\Exception as Exception;

    class Model extends Base
    {
        /**
         * @readwrite
         */
        protected $_table;

        /**
         * @readwrite
         */
        protected $_connector;

        /**
         * @read
         */
        protected $_types = array(
            "autonumber",
            "text",
            "integer",
            "decimal",
            "boolean",
            "datetime",
            "double",
        );

        /**
         * @read
         */
        protected $_validators = array(
            "required" => array(
                "handler" => "_validateRequired",
                "message" => "The {0} field is required"
            ),
            "alpha" => array(
                "handler" => "_validateAlpha",
                "message" => "The {0} field can only contain letters"
            ),
            "numeric" => array(
                "handler" => "_validateNumeric",
                "message" => "The {0} field can only contain numbers"
            ),
            "alphanumeric" => array(
                "handler" => "_validateAlphaNumeric",
                "message" => "The {0} field can only contain letters and numbers"
            ),
            "max" => array(
                "handler" => "_validateMax",
                "message" => "The {0} field must contain less than {2} characters"
            ),
            "min" => array(
                "handler" => "_validateMin",
                "message" => "The {0} field must contain more than {2} characters"
            )
        );

        /**
         * @read
         */
        protected $_errors = array();

        protected $_columns;

        protected $_primary;
        /**
         * @var array
         */
        public $joins = array();

        public function _getExceptionForImplementation($method)
        {
            return new Exception\Implementation("{$method} method not implemented");
        }

        public function __construct($options = array())
        {
            parent::__construct($options);
            //$this->load();
        }



//        public function __sleep(){
//            $array = array('_table', '_errors','_columns','_primary','_types');
//            $columns = $this->getColumns();
//
//                foreach ($this->columns as $key => $column){
//                    if (!$column["read"]){
//                        $array[] = $key;
//                    }
//                }
//
//            return $array;
//        }

        public function __destruct(){
            unset($this->connector);
        }

        public function load()
        {
            $primary = $this->primaryColumn;

            $raw = $primary["raw"];
            $name = $primary["name"];

            if (!empty($this->$raw))
            {
                $previous = $this->connector
                    ->query()
                    ->from($this->table)
                    ->where("{$name}", $this->$raw)
                    ->first();

                if ($previous == null)
                {
                    throw new Exception\Primary("Primary key value invalid");
                }

                foreach ($previous as $key => $value)
                {
                    $prop = "_{$key}";
                    if (!empty($previous->$key) && !isset($this->$prop))
                    {
                        $this->$key = $previous->$key;
                    }
                }
            }
        }

        public function delete()
        {
            $primary = $this->primaryColumn;

            $raw = $primary["raw"];
            $name = $primary["name"];

            if (!empty($this->$raw))
            {
                return $this->connector
                    ->query()
                    ->from($this->table)
                    ->where("{$name}", $this->$raw)
                    ->delete();
            }
        }

        public static function deleteAll($where = array())
        {
            $instance = new static();

            $query = $instance->connector
                ->query()
                ->from($instance->table);

            foreach ($where as $clause => $value)
            {
                $query->where($clause, $value);
            }

            return $query->delete();
        }

        public function save()
        {
            $primary = $this->primaryColumn;

            $raw = $primary["raw"];
            $name = $primary["name"];

            $query = $this->connector
                ->query()
                ->from($this->table);

            if (!empty($this->$raw))
            {
                $query->where($name, $this->$raw);
            }

            $data = array();
            foreach ($this->columns as $key => $column)
            {
                if (!$column["read"])
                {
                    $prop = $column["raw"];
                    $query->_params[$key] = $this->$prop;
                    $data[$key] = $this->$prop;
                    continue;
                }

                if ($column != $this->primaryColumn && $column)
                {
                    $method = "get".ucfirst($key);
                    $query->_params[$key] = $this->$method();
                    $data[$key] = $this->$method();
                    continue;
                }
            }

            $result = $query->save($data);

            if ($result > 0)
            {
                $this->$raw = $result;
            }

            return $result;
        }

        public function getTable()
        {
            if (empty($this->_table))
            {
                $className = StringMethods::classNameWithoutNamespace(get_class($this));

                $this->_table = strtolower(StringMethods::singular($className));
            }

            return $this->_table;
        }

        public function getConnector()
        {
            if (empty($this->_connector))
            {
                $database = Registry::get("database");

                if (!$database)
                {
                    throw new Exception\Connector("No connector available");
                }

                $this->_connector = $database->initialize();
            }

            return $this->_connector;
        }

        public function getColumns()
        {
            if (empty($_columns))
            {
                $primaries = 0;
                $columns = array();
                $class = StringMethods::classNameWithoutNamespace(get_class($this));

                $types = $this->types;

                $inspector = new Inspector($this);
                $properties = $inspector->getClassProperties();

                $first = function($array, $key)
                {
                    if (!empty($array[$key]) && sizeof($array[$key]) == 1)
                    {
                        return $array[$key][0];
                    }
                    return null;
                };

                foreach ($properties as $property)
                {
                    $propertyMeta = $inspector->getPropertyMeta($property);

                    if (!empty($propertyMeta["@column"]))
                    {
                        $name = preg_replace("#^_#", "", $property);
                        $primary = !empty($propertyMeta["@primary"]);
                        $type = $first($propertyMeta, "@type");
                        $length = $first($propertyMeta, "@length");
                        $index = !empty($propertyMeta["@index"]);
                        $readwrite = !empty($propertyMeta["@readwrite"]);
                        $read = !empty($propertyMeta["@read"]) || $readwrite;
                        $write = !empty($propertyMeta["@write"]) || $readwrite;

                        $validate = !empty($propertyMeta["@validate"]) ? $propertyMeta["@validate"] : false;
                        $label = $first($propertyMeta, "@label");

                        if (!in_array($type, $types))
                        {
                            throw new Exception\Type("{$type} is not a valid type");
                        }

                        if ($primary)
                        {
                            $primaries++;
                        }

                        $columns[$name] = array(
                            "raw" => $property,
                            "name" => $name,
                            "primary" => $primary,
                            "type" => $type,
                            "length" => $length,
                            "index" => $index,
                            "read" => $read,
                            "write" => $write,

                            "validate" => $validate,
                            "label" => $label
                        );
                    }
                }

                if ($primaries !== 1)
                {
                    throw new Exception\Primary("{$class} must have exactly one @primary column");
                }

                $this->_columns = $columns;
            }

            return $this->_columns;
        }

        public function getColumn($name)
        {
            if (!empty($this->_columns[$name]))
            {
                return $this->_columns[$name];
            }
            return null;
        }

        public function getPrimaryColumn()
        {
            if (!isset($this->_primary))
            {
                $primary = null;

                foreach ($this->columns as $column)
                {
                    if ($column["primary"])
                    {
                        $primary = $column;
                        break;
                    }
                }

                $this->_primary = $primary;
            }

            return $this->_primary;
        }

        /**
         * @param array $where
         * @param array $fields
         * @param null $order
         * @param null $direction
         * @return mixed
         */
        public static function first($where = array(), $fields = array("*"), $order = null, $direction = null)
        {
            $model = new static();
            return $model->_first($where, $fields, $order, $direction);
        }

        /**
         * @param array $where
         * @param array $fields
         * @param null $order
         * @param null $direction
         * @return mixed
         */
        public static function firstWithJoin($where = array(), $fields = array("*"), $order = null, $direction = null)
        {
            $model = new static();
            $joins = array();
            if (count($model->joins) != 0) $joins = $model->joins;
            return $model->_first($where, $fields, $order, $direction, $joins);
        }

        /**
         * @param array $where
         * @param array $fields
         * @param null $order
         * @param null $direction
         * @param array $joins
         * @return null
         */
        protected function _first($where = array(), $fields = array("*"), $order = null, $direction = null, $joins = array())
        {
            $query = $this
                ->connector
                ->query()
                ->from($this->table, $fields);

            foreach ($joins as $table => $values) {
                $query->join($table,$values['ON'], $values['fields']);
            }

            foreach ($where as $clause => $value)
            {
                if(is_array($value)){
                    if (!isset($value[1])){
                        $query->where($clause, $value[0]);
                    }else{
                        $query->where($clause, $value[0], $value[1]);
                    }
                }else{
                    $query->where($clause, $value);
                }
            }

            if ($order != null)
            {
                $query->order($order, $direction);
            }

            $first = $query->first();
//            $class = StringMethods::classNameWithoutNamespace(get_class($this));
            $class = get_class($this);

            if ($first)
            {
                $newUser =  new $class(
                    $first
                );
                return $newUser;
            }

            return null;
        }

        /**
         * @param array $where
         * @param array $fields
         * @param null $order
         * @param null $direction
         * @param null $limit
         * @param null $page
         * @param null $group
         * @return mixed
         */
        public static function all($where = array(), $fields = array("*"), $order = null, $direction = null, $limit = null, $page = null,$group = null)
        {
            $model = new static();
            return $model->_all($where, $fields, $order, $direction, $limit, $page, null,$group);
        }

        /**
         * @param array $where
         * @param array $fields
         * @param null $order
         * @param null $direction
         * @param null $limit
         * @param null $page
         * @param null $allJoins - the property from model
         * @param $group used for GROUP BY
         * @throws
         * @return mixed
         */
        public static function allWithJoins($where = array(), $fields = array("*"), $order = null, $direction = null, $limit = null, $page = null, $allJoins = null, $group = null)
        {
            $model = new static();
            $joins = array();
            if (count($model->joins) != 0){
                if (!is_null($allJoins)){
                    if (!property_exists(get_class($model), $allJoins)){
                        throw new Model\Exception('The join property is not defined');
                    }
                    $joins = $model->$allJoins;
                }else{
                    $joins = $model->joins;

                }
            }




            return $model->_all($where, $fields, $order, $direction, $limit, $page, $joins, $group);
        }

        /**
         * @param array $where
         * @param array $fields
         * @param null $order
         * @param null $direction
         * @param null $limit
         * @param null $page
         * @param array $joins
         * @param null $group
         * @return array
         */
        protected function _all($where = array(), $fields = array("*"), $order = null, $direction = null, $limit = null, $page = null, $joins = array(), $group = null)
        {
            $query = $this
                ->connector
                ->query()
                ->from($this->table, $fields);

            foreach ($joins as $table => $values) {
                $query->join($table,$values['ON'], $values['fields']);
            }


            foreach ($where as $clause => $value)
            {
                if(is_array($value)){
                    if (!isset($value[1])){
                        $query->where($clause, $value[0]);
                    }else{
                        $query->where($clause, $value[0], $value[1]);
                    }
                }else{
                    $query->where($clause, $value);
                }
            }

            if ($order != null){
                $query->order($order, $direction);
            }

            if ($group != null){
                $query->group($group);
            }

            if ($limit != null)
            {
                $query->limit($limit, $page);
            }

            $rows = array();
            $class = StringMethods::classNameWithoutNamespace(get_class($this));

            foreach ($query->all() as $row)
            {
                $rows[] = new $class(
                    $row
                );
            }

            return $rows;
        }

        /**
         * @param array $where
         * @param $allJoins - takes into account joins
         * @param null $group - GROUP BY
         * @return mixed
         * @throws
         */
        public static function countWithJoins($where = array(),$allJoins = null,$group = null){

            $model = new static();

            $joins = array();
            if (count($model->joins) != 0){
                if (!is_null($allJoins)){
                    if (!property_exists(get_class($model), $allJoins)){
                        throw new Model\Exception('The join property is not defined');
                    }
                    $joins = $model->$allJoins;
                }else{
                    $joins = $model->joins;

                }
            }
            return $model->_count($where, $group, $joins);
        }

        /**
         * @param array $where
         * @param $group
         * @return mixed
         */
        public static function count($where = array(), $group = null, $joins = null)
        {
            $model = new static();
            return $model->_count($where, $group, $joins);
        }

        /**
         * @param array $where
         * @param null $group
         * @param null $joins
         * @return mixed
         */
        protected function _count($where = array(),$group = null, $joins = null)
        {
            // query object
            $query = $this
                ->connector
                ->query()
                ->from($this->table);

            foreach ($joins as $table => $values) {
                $query->join($table,$values['ON'], $values['fields']);
            }

            foreach ($where as $clause => $value)
            {
                if(is_array($value)){
                    if (!isset($value[1])){
                        $query->where($clause, $value[0]);
                    }else{
                        $query->where($clause, $value[0], $value[1]);
                    }
                }else{
                    $query->where($clause, $value);
                }
            }

            if ($group != null){
                $query->group($group);
            }


            return $query->count();
        }

        protected function _validateRequired($value)
        {
            return !empty($value);
        }

        protected function _validateAlpha($value)
        {
            return StringMethods::match($value, "#^([a-zA-Z]+)$#");
        }

        protected function _validateNumeric($value)
        {
            return StringMethods::match($value, "#^([0-9]+)$#");
        }

        protected function _validateAlphaNumeric($value)
        {
            return StringMethods::match($value, "#^([a-zA-Z0-9]+)$#");
        }

        protected function _validateMax($value, $number)
        {
            return strlen($value) <= (int) $number;
        }

        protected function _validateMin($value, $number)
        {
            return strlen($value) >= (int) $number;
        }

        public function validate()
        {
            $this->_errors = array();

            foreach ($this->columns as $column)
            {
                if ($column["validate"])
                {
                    $pattern = "#[a-z]+\(([a-zA-Z0-9, ]+)\)#";

                    $raw = $column["raw"];
                    $name = $column["name"];
                    $validators = $column["validate"];
                    $label = $column["label"];

                    $defined = $this->getValidators();

                    foreach ($validators as $validator)
                    {
                        $function = $validator;
                        $arguments = array(
                            $this->$raw
                        );

                        $match = StringMethods::match($validator, $pattern);

                        if (count($match) > 0)
                        {
                            $matches = StringMethods::split($match[0], ",\s*");
                            $arguments = array_merge($arguments, $matches);
                            $offset = StringMethods::indexOf($validator, "(");
                            $function = substr($validator, 0, $offset);
                        }

                        if (!isset($defined[$function]))
                        {
                            throw new Exception\Validation("The {$function} validator is not defined");
                        }

                        $template = $defined[$function];

                        if (!call_user_func_array(array($this, $template["handler"]), $arguments))
                        {
                            $replacements = array_merge(array(
                                $label ? $label : $raw
                            ), $arguments);

                            $message = $template["message"];

                            foreach ($replacements as $i => $replacement)
                            {
                                $message = str_replace("{{$i}}", $replacement, $message);
                            }

                            if (!isset($this->_errors[$name]))
                            {
                                $this->_errors[$name] = array();
                            }

                            $this->_errors[$name][] = $message;
                        }
                    }
                }
            }

            return !sizeof($this->errors);
        }

    }
}