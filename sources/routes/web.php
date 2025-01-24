<?php
use App\Core\Router;
use App\Controllers\ImageController;
use App\Controllers\LoginController;
use App\Controllers\RegisterController;


$router->get("/login", LoginController::class, "index");
$router->post("/login", LoginController::class, "post");

$router->get("/articles/{slug}", ImageController::class, "index");

$router->get("/register", RegisterController::class, "index");
