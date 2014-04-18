<?php

include_once('framework'.DIRECTORY_SEPARATOR.'smooth.php');

//echo \Framework\Utility\StringMethods::getDelimiter();

class Hello extends Framework\Base {
    /**
     * @readwrite
     *
     */
    protected $_world;

//    public function setWorld($value){
//        echo "Your setter is being called";
//        $this->_world = $value;
//    }

//    public function getWorld(){
//        echo "your getter is being called";
//        return $this->_world;
//    }
}

$configuration = new Framework\Configuration(array(
    "type" => "ini"
));

$configuration = $configuration->initialize();
$configuration->parse(__DIR__.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'configuration'.DIRECTORY_SEPARATOR.'mysql');

