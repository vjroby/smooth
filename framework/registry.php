<?php

namespace Framework
{
    class Registry {
        /**
         * @var array
         */
        private static $_instances = array();

        private function __construct()
        {
            // do nothing
        }

        private function __clone()
        {
            // do nothing
        }

        /**
         * @param $key
         * @param null $default
         * @return null
         */
        public static function get($key, $default = null)
        {
            if (isset(self::$_instances[$key]))
            {
                return self::$_instances[$key];
            }
            return $default;
        }

        /**
         * @param $key
         * @param null $instance
         */
        public static function set($key, $instance = null)
        {
            self::$_instances[$key] = $instance;
        }

        /**
         * @param $key
         */
        public static function erase($key)
        {
            unset(self::$_instances[$key]);
        }
    }
}
 