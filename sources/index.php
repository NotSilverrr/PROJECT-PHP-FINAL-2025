<?php

require_once __DIR__ . "/core/Router.php";

require_once __DIR__ . "/controllers/LoginController.php";
require_once __DIR__ . "/controllers/RegisterController.php";
require_once __DIR__ . "/controllers/ImageController.php";

$router = new Router();

$router->get("/login", LoginController::class, "index");
$router->post("/login", LoginController::class, "post");

$router->get("/articles/{slug}", ImageController::class, "index");

$router->get("/register", RegisterController::class, "index");

$router->start();
