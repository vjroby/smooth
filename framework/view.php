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

        /**
         * @readwrite
         */
        protected $_actionFile;

        /**
         * @readwrite
         */
        protected $_content;

        public function __construct($options = array())
        {
            parent::__construct($options);


        }

        public function _getExceptionForImplementation($method)
        {
            return new Exception\Implementation("{$method} method not implemented");
        }

        public function render($output = false)
        {
            if (!file_exists($this->file))
            {
                throw new Exception\Renderer('No view file : '. basename($this->file, ".php"));
            }

            $data = $this->data;
            foreach ($data as $variable_name =>  $value) {
                $$variable_name = $value;
            }

            $data = $this->data;
            $content = $this->content;
            require ($this->file);
        }

        public function renderAction(){
            if (!file_exists($this->actionFile))
            {
                throw new Exception\Renderer('No view file for this action: '. basename($this->actionFile, ".php"));
            }
            $data = $this->data;
            foreach ($data as $variable_name =>  $value) {
                $$variable_name = $value;
            }


            ob_start();
            include($this->actionFile);
            $this->content = ob_get_contents();
            ob_end_clean();

        }

        protected function _set($key, $value)
        {
            if (!is_string($key) && !is_numeric($key))
            {
                throw new Exception\Data("Key must be a string or a number");
            }

            $data = $this->data;

            if (!$data)
            {
                $data = array();
            }

            $data[$key] = $value;
            $this->data = $data;
        }

        public function get($key, $default = "")
        {
            if (isset($this->data[$key]))
            {
                return $this->data[$key];
            }
            return $default;
        }

        public function set($key, $value = null)
        {
            if (is_array($key))
            {
                foreach ($key as $_key => $value)
                {
                    $this->_set($_key, $value);
                }
                return $this;
            }

            $this->_set($key, $value);
            return $this;
        }

        public function erase($key)
        {
            unset($this->data[$key]);
            return $this;
        }

        public function element($element, array $data = array()){
            $file = APP_PATH.DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'elements';
            $file .=DIRECTORY_SEPARATOR.$element.'.php';

            if (count($data) != 0 ){
                foreach ($data as $variable_name =>  $value) {
                    $$variable_name = $value;
                }

            }


            if (file_exists($file)){
                require ($file);
            }else{
                throw new Exception("Element file doesn't exists{$file}");
            }
        }
    }
}
 