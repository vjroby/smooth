<?php

namespace Framework\Utility
{

    class ArrayMethods {

        private function __construct()
        {
            // do nothing
        }

        private function __clone()
        {
            // do nothing
        }

        public static function clean($array)
        {
            return array_filter($array, function($item) {
                return !empty($item);
            });
        }

        public static function trim($array)
        {
            return array_map(function($item) {
                return trim($item);
            }, $array);
        }

        public static function toObject($array)
        {
            $result = new \stdClass();

            foreach ($array as $key => $value)
            {
                if (is_array($value))
                {
                    $result->{$key} = self::toObject($value);
                }
                else
                {
                    $result->{$key} = $value;
                }
            }

            return $result;
        }

        public static function flatten($array, $return = array()){

            foreach ($array as $key => $value) {

                if (is_array($value) || is_object($value)){

                    return self::flatten($value, $return);
                }else{

                    $return[] = $value;
                }
            }

            return $return;
        }

        public static function first($array)
        {
            if (sizeof($array) == 0)
            {
                return null;
            }

            $keys = array_keys($array);
            return $array[$keys[0]];
        }

        public static function last($array)
        {
            if (sizeof($array) == 0)
            {
                return null;
            }

            $keys = array_keys($array);
            return $array[$keys[sizeof($keys) - 1]];
        }

        /**
         *
         * prepares the values for echo in html
         *
         * @param $array
         * @return null
         */
        public static function prepareForHtml($array){
            if (sizeof($array) == 0){
                return null;
            }
            foreach ($array as $k => $v) {
                if(is_array($v)){
                    self::prepareForHtml($v);
                }else{
                    $array[$k] = StringMethods::prepareForHtml($v);
                }
            }

            return $array;

        }
    }


}