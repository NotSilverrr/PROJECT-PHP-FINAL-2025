<?php
namespace App\Controllers;

use App\Models\Group;
use App\Models\Photo;
use App\Models\User;
use App\Requests\GroupRequest;
use App\Requests\MemberRequest;
use App\Services\Auth;

class GroupController {
    public function show(int $id = -1) {

        if ($id == -1) {
            http_response_code(404);
            return view('group.index', ['error' => 'Select a Group']);
        }



        $group = Group::getOneById($id);
        $photos = Photo::findByGroupId($id);
        $members = Group::getMembers($id, $_GET['m'] ?? "");
        $allUsers = User::getAllUsers();

        // var_dump($_GET);



        if (Group::isMember($id)) {
            return view('group.index', ['id' => $id, 'group' => $group, 'photos' => $photos, 'members' => $members, 'allUsers' => $allUsers]);
        } else {
            http_response_code(403);
            return view('group.index', ['error' => 'You are not a member of this group']);
        }

    }

    public function getUsersGroups() {
        $userId = Auth::id();
        // $userId = 1;
        $groups = Group::getGroupsByUser($userId);

        return json_encode($groups);

    }

    public function deleteMember($id, $userId) {
        Group::deleteMember($id, $userId);
        header("Location: /group/$id");
        exit;
    }

    public function addMember() {
        
    }

    public function create() {
        return view('group.create');
    }

    public function store() {
        $request = new GroupRequest;
        try {
            $group = new Group(
                name: $request->name,
                profile_picture: $request->profile_picture,
                ownerId: Auth::id()
            );
            $group->createGroup();

            header("Location:/group/".$group->id);
        } catch(\Exception $e) {
            echo $e->getMessage();
        exit;
        }
    }


}