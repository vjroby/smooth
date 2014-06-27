<?php

namespace Shared
{
    use Framework\Registry;

    class Controller extends \Framework\Controller{

        /**
         * @readwrite
         */
        protected $_connector;

        /**
         * @readwrite
         */
        protected $_user;

        public function __construct($options = array()){

            parent::__construct($options);

            $database = Registry::get('database');
            $this->connector = $database->connect();

            $session = \Framework\Registry::get("session");
            $user = $session->get("user", null);
            $this->setUser($user);
        }

        public function render(){

            if ($this->getUser())
            {
                if ($this->getActionView())
                {
                    $this->getActionView()
                        ->set("user", $this->getUser());
                }

                if ($this->getLayoutView())
                {
                    $this->getLayoutView()
                        ->set("user", $this->getUser());
                }
            }

            parent::render();
        }
    }
}
 