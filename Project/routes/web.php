<?php
use App\Core\Router;
use App\Controllers\ImageController;
use App\Controllers\LoginController;
use App\Controllers\RegisterController;
use App\Controllers\TestController;

$router->get("/login", LoginController::class, "index");
$router->post("/login", LoginController::class, "post");
$router->get("/logout", LoginController::class, "delete");

$router->get("/articles/{slug}", ImageController::class, "index");

$router->get("/register", RegisterController::class, "index");
$router->post("/register", RegisterController::class, "post");

$router->get("/test", TestController::class, "test");
