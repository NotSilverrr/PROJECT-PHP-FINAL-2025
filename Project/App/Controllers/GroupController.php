<?php
namespace App\Controllers;

use App\Models\Group;
use App\Models\Photo;
use App\Services\Auth;

class GroupController {
    public function show(int $id) {

        $group = Group::getOneById($id);
        $photos = Photo::findByGroupId($id);
        $members = Group::getMembers($id);

        return view('group.index', ['id' => $id, 'group' => $group, 'photos' => $photos, 'members' => $members]);
    }

    public function getUsersGroups() {
        $userId = Auth::id();
        // $userId = 1;
        $groups = Group::getGroupsByUser($userId);

        return json_encode($groups);

    }
}