<?php

namespace Framework
{
    use Framework\Base as Base;
    use Framework\Events as Events;
    use Framework\Template as Template;
    use Framework\View\Exception as Exception;
    class View extends Base{

        /**
         * @readwrite
         */
        protected $_file;

        /**
         * @readwrite
         */
        protected $_data;

        /**
         * @read
         */
        protected $_template;

        public function __construct($options = array())
        {
            parent::__construct($options);


        }

        public function _getExceptionForImplementation($method)
        {
            return new Exception\Implementation("{$method} method not implemented");
        }

        public function render()
        {
            if (!file_exists($this->file))
            {
                return "";
            }

            return $this
                ->template
                ->parse(file_get_contents($this->file))
                ->process($this->data);
        }


    }
}
 