<?php
ini_set('memory_limit', '512M');
error_reporting( E_ALL | E_STRICT );

define("APP_PATH", dirname(dirname(__FILE__)));

require_once('../framework/smooth.php');
Framework\Smooth::initialize();