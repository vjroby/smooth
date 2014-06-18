<?php

namespace Shared
{
    use Framework\Registry;

    class Controller extends \Framework\Controller{

        public function __construct($options = array()){

            parent::__construct($options);

            $database = Registry::get('database');
            $database->connect();
        }
    }
}
 