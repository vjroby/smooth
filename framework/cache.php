<?php
namespace Framework
{
    use Framework\Base as Base;
    use Framework\Events as Events;
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

            Events::fire("framework.cache.initialize.before", array($this->type, $this->options));

            if (!$this->type)
            {
                $configuration = Registry::get("configuration");

                if ($configuration)
                {
                    $configuration = $configuration->initialize();
                    $parsed = $configuration->parse("configuration/cache");

                    if (!empty($parsed->cache->default) && !empty($parsed->cache->default->type))
                    {
                        $this->type = $parsed->cache->default->type;
                        unset($parsed->cache->default->type);
                        $this->options = (array) $parsed->cache->default;
                    }
                }
            }

            if (!$this->type)
            {
                throw new Exception\Argument("Invalid type");
            }

            Events::fire("framework.cache.initialize.after", array($this->type, $this->options));

            switch ($this->type)
            {
                case "memcached":
                {
                    return new Cache\Driver\Memcached($this->options);
                    break;
                }
                default:
                    {
                    throw new Exception\Argument("Invalid type");
                    break;
                    }
            }

        }
    }
}
 