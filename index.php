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

$hello = new Hello();
$hello->world = null;
echo $hello->world;
var_dump($hello->world);