<?php
namespace Framework
{
    use Framework\Base as Base;
    use Framework\Cache\Exception as Exception;

    class Cache extends Base{
        /**
         * @readwrite
         * @var
         */
        protected $_type;

        /**
         * @var
         * @readwrite
         */
        protected $_options;

        protected function _getExceptionForImplementation($method){
            return new Exception\Implementation("{$method} method not implemented");
        }

        public function initialize(){
            if (!$this->_type){
                throw new Exception\Argument("Invalid tyepe");
            }

            switch ($this->_type){

                case "memcached":
                    {
                        return new Cache\Driver\Memcached($this->_options);
                        break;
                    }
                default:
                    {
                    throw new Exception\Argument("Invalid type");
                    }

            }
        }
    }
}
 