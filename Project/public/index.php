<?php

use Core\Database;

require_once __DIR__ . '/../Helpers/helpers.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ROOT_PATH', dirname(__DIR__));


spl_autoload_register("myAutoloader");
function myAutoloader(string $class):void
{
    // $class = str_ireplace('App', '..',$class);
    $class =str_ireplace('\\', '/',$class).".php";

    $file = ROOT_PATH . '/' . $class;
    if(file_exists($file)){
        require_once $file;
    }
}

Database::getConnection();

use Core\Router;

// Initialiser le Router
$router = new Router();

// Charger les routes
require_once __DIR__ . './../routes/web.php';
require_once __DIR__ . './../routes/api.php';

// Démarrer le routage
$router->start();
