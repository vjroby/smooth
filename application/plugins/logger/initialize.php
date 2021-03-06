<?php

// initialize logger

include("logger.php");

$logger = new Logger(array(
    "dir" => APP_PATH . "/logs" ,
    "file" => date("Y-m-d") . ".txt",
    'type' => Logger::TYPE_INFO,
));

// log cache events

Framework\Events::add("framework.cache.initialize.before", function($type, $options) use ($logger)
{
    $logger->log("framework.cache.initialize.before: " . $type);
});

Framework\Events::add("framework.cache.initialize.after", function($type, $options) use ($logger)
{
    $logger->log("framework.cache.initialize.after: " . $type);
});

// log configuration events

Framework\Events::add("framework.configuration.initialize.before", function($type, $options) use ($logger)
{
    $logger->log("framework.configuration.initialize.before: " . $type);
});

Framework\Events::add("framework.configuration.initialize.after", function($type, $options) use ($logger)
{
    $logger->log("framework.configuration.initialize.after: " . $type);
});

// log controller events

Framework\Events::add("framework.controller.construct.before", function($name) use ($logger)
{
    $logger->log("framework.controller.construct.before: " . $name);
});

Framework\Events::add("framework.controller.construct.after", function($name) use ($logger)
{
    $logger->log("framework.controller.construct.after: " . $name);
});

Framework\Events::add("framework.controller.render.before", function($name) use ($logger)
{
    $logger->log("framework.controller.render.before: " . $name);
});

Framework\Events::add("framework.controller.render.after", function($name) use ($logger)
{
    $logger->log("framework.controller.render.after: " . $name);
});

Framework\Events::add("framework.controller.destruct.before", function($name) use ($logger)
{
    $logger->log("framework.controller.destruct.before: " . $name);
});

Framework\Events::add("framework.controller.destruct.after", function($name) use ($logger)
{
    $logger->log("framework.controller.destruct.after: " . $name);
});

// log database events

Framework\Events::add("framework.database.initialize.before", function($type, $options) use ($logger)
{
    $logger->log("framework.database.initialize.before: " . $type);
});

Framework\Events::add("framework.database.initialize.after", function($type, $options) use ($logger)
{
    $logger->log("framework.database.initialize.after: " . $type);
});

// log request events

Framework\Events::add("framework.request.request.before", function($method, $url, $parameters) use ($logger)
{
    $logger->log("framework.request.request.before: " . $method . ", " . $url);
});

Framework\Events::add("framework.request.request.after", function($method, $url, $parameters, $response) use ($logger)
{
    $logger->log("framework.request.request.after: " . $method . ", " . $url);
});

// log router events

Framework\Events::add("framework.router.dispatch.before", function($url) use ($logger)
{
    $logger->log("framework.router.dispatch.before: " . $url);
});

Framework\Events::add("framework.router.dispatch.after", function($url, $controller, $action, $parameters) use ($logger)
{
    $logger->log("framework.router.dispatch.after: " . $url . ", " . $controller . ", " . $action);
});

// log session events

Framework\Events::add("framework.session.initialize.before", function($type, $options) use ($logger)
{
    $logger->log("framework.session.initialize.before: " . $type);
});

Framework\Events::add("framework.session.initialize.after", function($type, $options) use ($logger)
{
    $logger->log("framework.session.initialize.after: " . $type);
});

// log view events

Framework\Events::add("framework.view.construct.before", function($file) use ($logger)
{
    $logger->log("framework.view.construct.before: " . $file);
});

Framework\Events::add("framework.view.construct.after", function($file, $template) use ($logger)
{
    $logger->log("framework.view.construct.after: " . $file);
});

Framework\Events::add("framework.view.render.before", function($file) use ($logger)
{
    $logger->log("framework.view.render.before: " . $file);
});

$logger_error = new Logger(array(
    "dir" => APP_PATH . "/logs" ,
    "file" => date("Y-m-d") . ".txt",
    'type' => Logger::TYPE_ERROR,
));

if (!function_exists('main_error_handler')){

    function main_error_handler($errno, $errstr, $errfile, $errline){
        $logger_error = new Logger(array(
            "dir" => APP_PATH . "/logs" ,
            "file" => date("Y-m-d") . ".txt",
            'type' => Logger::TYPE_ERROR,
        ));
        $string = "[Error] ". "No. ".$errno. ", File: ".$errfile.", Line: ".$errline.", String: ".$errstr.";";

        $logger_error->log($string);
       // throw new \Framework\Core\Exception($string);
    }
    set_error_handler('main_error_handler');
}
if (!function_exists('main_exception_handler')){

    function main_exception_handler($exception) {
        GLOBAL $logger_error;
//            if(extension_loaded('xdebug')){
//                echo'<pre class="xdebug-var-dump">'.$exception->xdebug_message.'</pre>';
//
//            }else{
//
//                print_r($exception->getMessage());
//            }
        $logger_error->log("Exception",$exception->getCode(),$exception->getMessage());

    }
    set_exception_handler('main_exception_handler');

}