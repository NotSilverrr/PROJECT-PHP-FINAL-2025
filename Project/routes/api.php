<?php

use App\Controllers\GroupController;

$router->get("/api/groups", GroupController::class, "getUsersGroups");