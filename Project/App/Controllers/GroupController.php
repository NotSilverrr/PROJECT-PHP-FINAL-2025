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
            return view('group.index', ['message' => 'Select a Group']);
        }

        if (!Group::exist($id)) {
            return view('errors.404');
        }



        $group = Group::getOneById($id);
        $photos = Photo::findByGroupId($id);
        $members = Group::getMembers($id, $_GET['m'] ?? "");
        $allUsers = User::getAllUsers();

        // var_dump($_GET);



        if (Group::isMember($id, Auth::id())) {
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

    public function create() {
        return view('group.create');
    }

    public function store() {
        $request = new GroupRequest;
        
        try {

            if (empty($request->name)) {
                echo "Group name is required";
                exit;
              } elseif (strlen($request->name) > 50) {
                echo "Group name must be less than 50 characters";
                exit;
              }
          
              try {
                $owner = User::findOneById(Auth::id());
                if (!$owner) {
                  echo "Selected owner does not exist";
                  exit;
                }
              } catch (\Exception $e) {
                echo "Invalid owner selected";
                exit;
              }
            // Créer un groupe sans image d'abord
            $group = new Group(
                name: $request->name,
                profile_picture: null, // Temporairement null
                ownerId: Auth::id()
            );
    
            $group->createGroup();
    
            // Vérifier si une image est envoyée
            if (!empty($request->profile_picture['name'])) {
                $this->handleImageUpload($request->profile_picture, $group);
            }

            $_SESSION['success'] = "Votre compte a été créé avec succès.";
            header("Location:/group/" . $group->id);

            exit;
    
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    private function handleImageUpload(array $file, Group $group) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception("Erreur lors de l'upload de l'image.");
        }
    
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
        if (!in_array($fileExt, $allowedExts)) {
            throw new \Exception("Format non autorisé. JPG, JPEG, PNG, GIF et WEBP uniquement.");
        }
    
        if ($file['size'] > 5 * 1024 * 1024) {
            throw new \Exception("Fichier trop volumineux. Max 5 Mo.");
        }
    
        $uploadDir = __DIR__ ."/../../uploads/groups/" . $group->id . "/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
    
        $filePath = $uploadDir . "profile_picture." . $fileExt;
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new \Exception("Impossible de sauvegarder l'image.");
        }
    
        $group->profile_picture = "uploads/groups/" . $group->id . "/" . "profile_picture." . $fileExt;;
        $group->update();
    }


}