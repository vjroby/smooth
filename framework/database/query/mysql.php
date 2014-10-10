<?php
namespace Framework\Database\Query
{
    use Framework\Database as Database;
    use Framework\Database\Exception as Exception;

    class Mysql extends Database\Query{

        public function all()
        {
            $sql = $this->_buildSelect();
            $result = $this->connector->execute($sql, $this->_params);

            if ($result === false)
            {
                $error = $this->connector->lastError;
                throw new Exception\Sql("There was an error with your SQL query: {$error}");
            }

            $rows = array();
            $count = $result->rowCount();
            for ($i = 0; $i < $count; $i++)
            {
                $rows[] = $result->fetch(\PDO::FETCH_ASSOC);
            }

            return $rows;
        }

        /**
         * Helper function for all the where and having functions of this class.
         * @param string $field The column to wich this clause applies.
         * @param mixed $value [optional]
         * <p>The value to compare the column with.</p>
         * <p>Leave this parameter and the operator parameter blank (NULL) to have the "IS NULL" operator.</p>
         * <p>If this parameter is an array and no operator is given the "IN" operator will be used.</p>
         * <p>If this parameter is an array you can also specify the "NOT IN" operator as the next parameter.</p>
         * <p>If this parameter is a string and no operator is given the "=" operator will be used.</p>
         * @param string $operator [optional] <p>The operator that will be used (e.g. "IS NULL", "IN", "NOT IN", "=", "<>", ...).</p>
         * @param $concatenator
         * @param bool $date
         * @return string
         * A condition build based on the given parameters.
         */
        public function condition($field, $value, $operator, $concatenator, $date = false){
            if (!isset($operator) || $operator == "IN" || $operator == "NOT IN") {
                if (is_array($value)) {
                    if(!isset($operator)){
                        $operator = 'IN';
                    }
                    $v = '(';
                    $i=0;
                    foreach($value as $val){
                        $i++;
                        if ($date === true){
                            $v .= ':' . $field . $i . ",  ";
                        }else{
                            $v .= ':' . $field . $i . ', ';
                        }

                        $bind[':' . $field . $i] = $val;
                    }
                    $v = substr($v, 0, -2);
                    $v .= ')';
                    $placeholder = $v;
                }
                elseif (is_null($value)) {
                    $operator = 'IS NULL';
                }
                else {
                    $operator = '=';
                }
            }
            if(!isset($placeholder) && !is_null($value)){
                $placeholder = ':' . $field;
                $placeholder = str_replace('.', '', $placeholder);
                $i = 0;
                while(isset($this->params[$placeholder])){
                    $i++;
                    $placeholder = ':' . $field . $i;
                    $placeholder = str_replace('.', '', $placeholder);
                }
                $bind[$placeholder] = $value;
            }elseif(!isset($placeholder)){
                $placeholder = '';
            }
            if (isset($bind)){
                $this->params += $bind;

            }
            if(!empty($concatenator)){
                $concatenator = " " . trim($concatenator) . " ";
            }

            if ($date === true){
                $return = $concatenator ." TO_DATE(TO_CHAR(". $field . ",'DD/MM/YYYY'),'DD/MM/YYYY') " . $operator. "  TO_DATE(" . $placeholder.", 'DD/MM/YYYY') ";
            }else{
                $return = $concatenator . $field . " " . $operator. " " . $placeholder;

            }

            return $return;
        }
    }
}
 