<?php
namespace Framework\Router\Route
{
    use Framework\Router as Router;

    class Regex extends Router\Route{
        /**
         * @readwrite
         */
        protected  $_keys;

        public function matches($url){
            $pattern = $this->_pattern;

            // check values
            preg_match_all("#^{$pattern}$#", $url, $values);

            if (count($values) && count($values[0]) && count($values[1])){
                // values found, modify parameters and return
                $derived = array_combine($this->_keys, $values[1]);
                $this->_parameters = array_merge($this->_parameters, $derived);

                return true;
            }
            return false;
        }
    }
}
 