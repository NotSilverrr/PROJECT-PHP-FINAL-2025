<?php

use App\Controllers\Admin\AdminController;
use App\Controllers\GroupController;
use App\Core\Router;
use App\Controllers\ImageController;
use App\Controllers\LoginController;
use App\Controllers\RegisterController;
use App\Controllers\TestController;
use App\Controllers\admin\AdminUserController;
use App\Controllers\admin\AdminGroupController;
use App\Controllers\admin\AdminPhotoController;

$router->get("/login", LoginController::class, "index");
$router->post("/login", LoginController::class, "post");
$router->get("/logout", LoginController::class, "delete");

$router->get("/articles/{slug}", ImageController::class, "index");

$router->get("/register", RegisterController::class, "index");
$router->post("/register", RegisterController::class, "post");

$router->get("/test", TestController::class, "test");


$router->get("/admin/user", AdminUserController::class, "index");
$router->post("/admin/user/delete", AdminUserController::class, "delete");
$router->get("/admin/group", AdminGroupController::class, "index");
$router->get("/admin/photo", AdminPhotoController::class, "index");

$router->get("/group/{id}", GroupController::class, "show");
