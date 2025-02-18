<?php
namespace App\Controllers;

use App\Models\Group;
use App\Models\Photo;
use App\Models\User;
use App\Requests\GroupRequest;
use App\Services\GroupService;
use App\Requests\MemberRequest;
use App\Services\Auth;
use App\Services\ImageService;

class GroupController {
    public function show(int $id = -1) {

        if ($id == -1) {
            http_response_code(404);
            return view('group.index', ['message' => 'Select a Group']);
        }

        if (!Group::exist($id)) {
            return view('errors.404');
        }

        $group = Group::getOneById($id);
        $photos = Photo::findByGroupId($id);
        $members = Group::getMembers($id, $_GET['m'] ?? "");
        $allUsers = User::getAllUsers();


        if ($group->isMember(Auth::id())) {
            return view('group.index', ['id' => $id, 'group' => $group, 'photos' => $photos, 'members' => $members, 'allUsers' => $allUsers]);
        } else {
            http_response_code(403);
            return view('group.index', ['error' => 'You are not a member of this group']);
        }

    }

    public function getUsersGroups() {
        $userId = Auth::id();
        $groups = Group::getGroupsByUser($userId);

        return json_encode($groups);

    }

    public function deleteMember($id, $userId) {
        Group::deleteMember($id, $userId);
        header("Location: /group/$id");
        exit;
    }

    public function create() {
        startSession();
        return view('group.create');
    }

    public function store() {
        $request = new GroupRequest;
        $service = new GroupService($request);
        try {
            $group = new Group(
                name: $request->name,
                profile_picture: null,
                ownerId: Auth::id()
            );

            $_SESSION['group_create'] = $group;

            $error = $service->validate_name();
            if ($error !== null) {
                throw new \Exception($error);
            }
        
            $error = $service->check_owner_exist();
            if ($error !== null) {
                throw new \Exception($error);
            }

            $error = $service->validate_profile_picture();
            if ($error !== null) {
                throw new \Exception($error);
            }

            $group->createGroup();

            $uploadDir = "uploads/groups/". $group->id;
            $fileName = ImageService::uploadPhoto($request->profile_picture, $uploadDir);

            $error = $service->validate_profile_picture_save($fileName);
            if ($error !== null) {
                throw new \Exception($error);
            }
            $group->profile_picture = $fileName;
            $group->update();

            $_SESSION['success'] = "Votre groupe a été créé avec succès.";
            header("Location:/group/" . $group->id);

            exit;
    
        } catch (\Exception $e) {
            if (isset($group)) {
                $group->delete();
            }
            $_SESSION['error'] = $e->getMessage();
            header("Location:/group/create");
            exit;
        }
    }

    public function delete($id) {
        $group = Group::getOneById($id);
        if (!($group->isOwner(Auth::id()) || Auth::user()->isAdmin())) {
            http_response_code(403);
            return view('errors.403');
        }
        $folderPath = "uploads/groups/" . $id;
        deleteFolder($folderPath);

        $group->delete();
        $_SESSION['success'] = "Groupe supprimé avec succès";
        header("Location: /");
        exit;
    }

    public function showGroupProfilePicture($id) {
        $group = Group::getOneById($id);
        if (!$group) {
            return view('errors.404');
        }
        if (!$group->isMember(Auth::id())) {
            http_response_code(403);
            return view('errors.403');
        } 
        $path = $group->profile_picture;
        ImageService::serve($path);
    }


}