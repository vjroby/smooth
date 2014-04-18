<?php

namespace Framework\Check;
use Framework;

class Environment {

    public static function  check_php_version(){
        if (version_compare(phpversion(), '5.3.10', '<')) {
            throw new Framework\Core\Exception\Environment('php version isn\'t high enough you must have at least 5.3.10');
        }
    }
} 