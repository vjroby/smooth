<?php

include_once('framework'.DIRECTORY_SEPARATOR.'smooth.php');


$configuration = new Framework\Configuration(array(
    "type" => "ini"
));

$configuration = $configuration->initialize();
$configuration->parse(__DIR__.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'configuration'.DIRECTORY_SEPARATOR.'mysql');

//$router = new Framework\Router();
//$router->addRoute(
//    new Framework\Router\Route\Simple(array(
//        "pattern" => ":name/profile",
//        "controller" => "home",
//        "action" => "index"
//    ))
//);
//$url = $_SERVER['REQUEST_URI'];
//$url = str_replace('/smooth/','',$url);
//
//if(strlen($url) !== 0){
//    $router->url = $url;
//    $router->dispatch();
//}



$database= new Framework\Database(array(
    "type" =>"mysql",
    "options" =>array(
        "host" =>"localhost",
        "username" =>"root",
        "password" =>"root",
        "schema" =>"prophpmvc",
        "port" =>"3306"
    )
));

$database = $database->initialize()->connect();


$all = $database->query()
    ->from("users", array(
        "first_name",
        "last_name" => "surname"
    ))
//    ->join("points", "points.id = users.id", array(
//        "points" => "rewards"
//    ))
    ->where("first_name = ?", "Gigel")
    ->order("last_name", "desc")
    ->limit(100)
    ->all();

$print = print_r($all, true);
echo "all => {$print}";

$affected = $database->query()->from("users")
        ->where("first_name=?", "Liz")->delete();
echo "affected =>{$affected}\n";


$count = $database->query()->from("users")
    ->count();
echo "count =>{$count}\n";
echo '<div>test</div>';

\Framework\Test::start();

var_dump(\Framework\Test::run());
