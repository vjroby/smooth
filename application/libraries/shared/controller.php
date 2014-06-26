<?php

namespace Shared
{
    use Framework\Registry;

    class Controller extends \Framework\Controller{

        /**
         * @readwrite
         */
        protected $_connector;

        public function __construct($options = array()){

            parent::__construct($options);

            $database = Registry::get('database');
            $this->connector = $database->connect();
        }
    }
}
 