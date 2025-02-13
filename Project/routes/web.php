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
$router->get("/admin/user/update/{id}", AdminUserController::class, "updateIndex");
$router->post("/admin/user/update", AdminUserController::class, "update");
$router->get("/admin/user/add", AdminUserController::class, "addIndex");
$router->post("/admin/user/add", AdminUserController::class, "add");

$router->get("/admin/group", AdminGroupController::class, "index");
$router->post("/admin/group/delete", AdminGroupController::class, "delete");
$router->get("/admin/group/update/{id}", AdminGroupController::class, "updateIndex");
$router->post("/admin/group/update", AdminGroupController::class, "update");
$router->get("/admin/group/add", AdminGroupController::class, "addIndex");
$router->post("/admin/group/add", AdminGroupController::class, "add");

$router->get("/admin/photo", AdminPhotoController::class, "index");
$router->post("/admin/photo/delete", AdminPhotoController::class, "delete");
$router->get("/admin/photo/update/{id}", AdminPhotoController::class, "updateIndex");
$router->post("/admin/photo/update", AdminPhotoController::class, "update");
$router->get("/admin/photo/add", AdminPhotoController::class, "addIndex");
$router->post("/admin/photo/add", AdminPhotoController::class, "add");

$router->get("/group/{id}", GroupController::class, "show");
