<?php

namespace App\Controllers;

use App\Models\Group;
use App\Models\Member;
use App\Models\Photo;
use App\Models\User;
use App\Requests\PhotoRequest;
use App\Services\Auth;
use App\Services\ImageService;

class PhotoController
{
  public function create(int $id)
  {
    $members = Group::getMembers($id, $_GET['m'] ?? "");
    $group = Group::getOneById($id);
    return view('group.upload', ["members" => $members, "group" => $group]);
  }
  public function store($groupId)
    {
        if (!Group::isMember($groupId, Auth::id())) {
            return view('errors.403');
        }
        if (!Member::canEdit($groupId, Auth::id())) {
            return view('errors.403');
        }
        $request = new PhotoRequest();

        if (!$request->validate()) {
            return "Fichier non valide.";
        }

        $filePath = ImageService::uploadPhoto($request->file, $groupId);

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
        if (!Group::isMember($groupId, Auth::id())) {
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
        if (!Group::isMember($groupId, Auth::id())) {
            print_r("You are not a member of this group");
            return view('errors.403');
        }
        
        if (!(Photo::isOwner($photoId, Auth::id()) || Auth::user()->isAdmin() || Group::isOwner($groupId))) {
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

}