<?php

use App\Controllers\GroupController;
use App\Core\Router;
use App\Controllers\ImageController;
use App\Controllers\LoginController;
use App\Controllers\RegisterController;
use App\Controllers\TestController;
use App\Controllers\PhotoController;

use App\Controllers\admin\AdminUserController;
use App\Controllers\admin\AdminGroupController;
use App\Controllers\admin\AdminPhotoController;
use App\Controllers\MemberController;
use App\Controllers\PasswordResetController;
use App\Controllers\admin\AdminMemberController;
use App\Controllers\UserController;

$router->redirect("/", "/group");
$router->redirect("/admin", "/admin/user");

$router->get("/login", LoginController::class, "index");
$router->post("/login", LoginController::class, "post");
$router->get("/logout", LoginController::class, "delete");
$router->get("/password-reset", PasswordResetController::class, "showForm");
$router->post("/password-reset", PasswordResetController::class, "sendResetLink");
$router->get("/reset-password", PasswordResetController::class, "showResetForm");
$router->post("/reset-password", PasswordResetController::class, "resetPassword");

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

$router->post("/admin/member/add/{id}", AdminMemberController::class, "add");
$router->post("/admin/member/delete/{id}", AdminMemberController::class, "delete");
$router->post("/admin/member/toggle-readonly/{id}", AdminMemberController::class, "toggleReadonly");

$router->get("/admin/photo", AdminPhotoController::class, "index");
$router->post("/admin/photo/delete", AdminPhotoController::class, "delete");
$router->get("/admin/photo/update/{id}", AdminPhotoController::class, "updateIndex");
$router->post("/admin/photo/update", AdminPhotoController::class, "update");
$router->get("/admin/photo/add", AdminPhotoController::class, "addIndex");
$router->post("/admin/photo/add", AdminPhotoController::class, "add");

$router->get("/group", GroupController::class, "show");
$router->get("/group/create", GroupController::class, "create");
$router->post("/group", GroupController::class, "store");
$router->get("/group/{id}", GroupController::class, "show");
$router->post("/group/{id}/delete", GroupController::class, "delete");
$router->get("/group/{id}/profilePicture", GroupController::class, "showGroupProfilePicture");
$router->get("/group/{id}/addMember", MemberController::class, "create");
$router->post("/group/{id}/addMember", MemberController::class, "store");
$router->get("/group/{id}/upload", PhotoController::class, "create");
$router->post("/group/{groupId}/upload", PhotoController::class, "store");
$router->get("/group/{groupId}/showImage/{photoId}", PhotoController::class, "show");
$router->post("/group/{groupId}/deleteImage/{photoId}", PhotoController::class, "delete");
$router->get("/group/{groupId}/user/{userid}", MemberController::class, "show");
$router->post("/group/{groupId}/user/{userid}", MemberController::class, "update");


$router->post("/group/{id}/deleteUser", MemberController::class, "delete");
$router->get("/image/{id}", ImageController::class, "show");

$router->get("/user/{id}/profilePicture", UserController::class, "showProfilePicture");

$router->get("/photos/{id}", PhotoController::class, "showGuest");

