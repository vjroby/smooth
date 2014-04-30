<?php

include_once('framework'.DIRECTORY_SEPARATOR.'smooth.php');


$configuration = new Framework\Configuration(array(
    "type" => "ini"
));

$configuration = $configuration->initialize();
$configuration->parse(__DIR__.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'configuration'.DIRECTORY_SEPARATOR.'mysql');

class Home extends Framework\Controller
{

    /**
     * @once
     * @protected
     */
    public function init()
    {
        echo "init".PHP_EOL;
    }

    /**
     * @protected
     */
    public function authenticate()
    {
        echo "authenticate".PHP_EOL;
    }

    /**
     * @before init, authenticate, init
     * @after notify
     */
    public function index()
    {
        echo "hello world!";
        print_r($this->_parameters);
    }

    /**
     * @after notify
     */
    public function newaction(){
        echo 'New Action!';
    }

    /**
     * @protected
     */
    public function notify()
    {
        echo "notify".PHP_EOL;
    }
}

$router = new Framework\Router();
$router->addRoute(
    new Framework\Router\Route\Simple(array(
        "pattern" => ":name/profile",
        "controller" => "home",
        "action" => "index"
    ))
);
$url = $_SERVER['REQUEST_URI'];
$url = str_replace('/smooth/','',$url);

if(strlen($url) !== 0){
    $router->url = $url;
    $router->dispatch();
}

