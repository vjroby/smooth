<?php

namespace Shared
{
    use Framework\Registry as Registry;
    use Framework\Events as Events;
    use Framework\Router as Router;

    class Controller extends \Framework\Controller{

        /**
         * @readwrite
         */
        protected $_connector;

        /**
         * @readwrite
         */
        protected $_user;

        /**
         * @protected
         */
        public function _admin()
        {
            if (!$this->user->admin)
            {
                throw new Router\Exception\Controller("Not a valid admin user account");
            }
        }

        public function __construct($options = array()){

            parent::__construct($options);

            $database = Registry::get('database');
            $this->connector = $database->connect();

            // schedule disconnect from database
            Events::add("framework.controller.destruct.after", function($name) {
                $database = Registry::get("database");
                $database->disconnect();
            });

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

        public function setUser($user)
        {
            $session = Registry::get("session");

            if ($user)
            {
                $session->set("user", $user);
            }
            else
            {
                $session->erase("user");
            }

            $this->_user = $user;
            return $this;
        }
    }
}
 