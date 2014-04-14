<?php
define('SMOOTH_PATH',__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);

function autoload($class)
{

    $file = strtolower(str_replace("\\", DIRECTORY_SEPARATOR, trim($class, "\\"))).".php";


        $combined = SMOOTH_PATH.DIRECTORY_SEPARATOR.$file;

        if (file_exists($combined))
        {
            include($combined);
            return;
        }


    throw new Exception("{$class} not found");
}

class Autoloader
{
    public static function autoload($class)
    {
        autoload($class);
    }

}

spl_autoload_register('autoload');
spl_autoload_register(array('autoloader', 'autoload'));
