<?php

namespace App\Controllers;

use App\Models\Group;
use App\Models\Member;
use App\Models\Photo;
use App\Requests\PhotoRequest;
use App\Services\Auth;
use App\Services\ImageService;
use App\Services\PhotoService;

class PhotoController
{
  public function create(int $id)
  {
    startSession();
    $members = Group::getMembers($id, $_GET['m'] ?? "");
    $group = Group::getOneById($id);
    return view('group.upload', ["members" => $members, "group" => $group]);
  }
  public function store($groupId)
    {
        startSession();
        $group = Group::getOneById($groupId);
        if (!$group->isMember(Auth::id())) {
            return view('errors.403');
        }
        if (!Member::canEdit($groupId, Auth::id())) {
            return view('errors.403');
        }
        $request = new PhotoRequest();
        $service = new PhotoService($request);

        $error = $service->check_user_exist();
        if ($error !== null) {
          $_SESSION['error'] = $error;
        }
    
        $error = $service->check_group_exist();
        if ($error !== null) {
          $_SESSION['error'] = $error;
        }
    
        $error = $service->validate_file();
        if ($error !== null) {
          $_SESSION['error'] = $error;
        }

        if (isset($_SESSION['error'])) {
            header("Location:/group/$groupId/upload");
        }

        $uploadDir = "/uploads/groups/" . $groupId;
        try {
            $filePath = ImageService::uploadPhoto($request->file, $uploadDir);
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location:/group/$groupId/upload");
            exit;
        }

        if ($filePath) {
            $photo = new Photo(
                file: $filePath, 
                group_id: $groupId, 
                user_id: Auth::id()
                );
            $photo->createPhoto();
            $_SESSION['success'] = "Photo ajoutée avec succès";
            header("Location:/group/$groupId");
            exit;
        }
        header("Location:/group/$groupId");
        exit;
    }

    public function show($groupId, $photoId)
    {
        $group = Group::getOneById($groupId);
        if (!$group->isMember(Auth::id())) {
            return view('errors.403');
        }
        $photo = Photo::findOneById($photoId);
        if (!$photo) {
            return view('errors.404');
        }
        $path = $photo->file;
        if (!file_exists(__DIR__ . "/../../".$path)) {
            return view('errors.404');
        }
        ImageService::serve($path);
    }

    public function delete($groupId, $photoId)
    {
        $group = Group::getOneById($groupId);
        if (!$group->isMember(Auth::id())) {
            print_r("You are not a member of this group");
            return view('errors.403');
        }
        
        if (!(Photo::isOwner($photoId, Auth::id()) || Auth::isAdmin() || $group->isOwner(Auth::id()))) {
            return view('errors.403');
        }
        $photo = Photo::findOneById($photoId);
        if (!$photo) {
            return view('errors.404');
        }
        ImageService::delete($photo->file);
        $photo->deletePhoto();
        $_SESSION['success'] = "Photo supprimée avec succès";
        header("Location:/group/$groupId");
        exit;
    }

    public function share(int $id)
    {
        $photo = Photo::findOneById($id);
        if (!$photo) {
            return view('errors.404');
        }
        $group = Group::getOneById($photo->group_id);
        if (!$group->isMember(Auth::id())) {
            return view('errors.403');
        }
        $shareToken = bin2hex(random_bytes(32));

        
        $expiration = new \DateTime('+1 hour');
        $photo->saveShareToken($shareToken, $expiration);

        $shareLink = $_ENV["HOST_NAME"]."/photos/$id?token=$shareToken";

        $_SESSION['success'] = "Lien de partage copié dans le presse-papiers";
        return json_encode(['shareLink' => $shareLink]);
        exit;
    }

    public function showGuest(int $id)
    {
        $photo = Photo::findOneById($id);
        if (!$photo) {
            return view('errors.404')->layout('guest');
        }
        if (!$photo->share_token) {
            return view('errors.403')->layout('guest');
        }
        if ($photo->share_token_expiration < new \DateTime()) {
            return view('errors.403')->layout('guest');
        }
        if (!hash_equals($photo->share_token, $_GET['token'])) {
            return view('errors.403')->layout('guest');
        }
        $path = $photo->file;
        if (!file_exists(__DIR__ . "/../../".$path)) {
            return view('errors.404')->layout('guest');
        }
        ImageService::serve($path);
    }

}