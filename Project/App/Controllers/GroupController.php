<?php
namespace App\Controllers;

use App\Models\Group;
use App\Models\Photo;

class GroupController {
    public function show(int $id) {

        $group = Group::getOneById($id);
        $photos = Photo::findByGroupId($id);

        return view('group.index', ['id' => $id, 'group' => $group, 'photos' => $photos]);
    }
}