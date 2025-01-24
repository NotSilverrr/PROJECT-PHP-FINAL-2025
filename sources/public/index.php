<?php

require_once __DIR__ . '/../Helpers/helpers.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);


spl_autoload_register("myAutoloader");
function myAutoloader(string $class):void
{
    //    App\Core\User
    $class = str_ireplace('App', '..',$class);
    //    ..\Core\User
    $class =str_ireplace('\\', '/',$class).".php";
    //    Core/User.php
    if(file_exists($class)){
        //si le fichier existe : require "../Core/User.php";
        include $class;
    }
}

use App\Core\Router;

// Initialiser le Router
$router = new Router();

// Charger les routes
require_once __DIR__ . './../routes/web.php';

// Démarrer le routage
$router->start();
