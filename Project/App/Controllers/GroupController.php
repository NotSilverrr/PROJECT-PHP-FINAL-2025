<?php
namespace App\Controllers;

use App\Models\Group;

class GroupController {
    public function show(int $id) {

        $group = Group::getOneById($id);

        return view('group.index', ['id' => $id, 'group' => $group]);
    }
}