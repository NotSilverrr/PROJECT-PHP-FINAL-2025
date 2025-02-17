<?php

use App\Controllers\GroupController;
use App\Controllers\PhotoController;

$router->get("/api/groups", GroupController::class, "getUsersGroups");
$router->get("/api/photos/{id}/share", PhotoController::class, "share");