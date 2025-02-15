<?php
namespace App\Controllers\admin;

use App\Models\Photo;
use App\Models\User;
use App\Models\Group;
use Core\QueryBuilder;
use Core\Error;
use App\Controllers\ImageController;

class AdminPhotoController
{
  public static function index()
  {
    $queryBuilder = new QueryBuilder();
    $photos = $queryBuilder
      ->select(['photos.id', 'photos.file', 'groups.name as group_name', 'users.email as user_email'])
      ->from('photos')
      ->join('groups', 'photos.group_id', '=', 'groups.id')
      ->join('users', 'photos.user_id', '=', 'users.id')
      ->fetchAll();

    return view('admin.photo.photo', data: ['photos' => $photos])->layout('admin');
  }

  public static function delete()
  {
    $id = $_POST['id'];
    
    $queryBuilder = new QueryBuilder();
    $photo = $queryBuilder->select(['file'])->from('photos')->where('id', '=', $id)->fetch();
    
    if ($photo && file_exists($photo['file'])) {
      unlink($photo['file']);
    }
    
    $queryBuilder->delete()->from('photos')->where('id','=', $id)->execute();
    
    return redirect('/admin/photo');
  }

  public static function updateIndex(int $id)
  {
    $queryBuilderPhoto = new QueryBuilder();
    $photo = $queryBuilderPhoto->select(['id', 'file', 'group_id', 'user_id'])->from('photos')->where('id', '=', $id)->fetch();

    $queryBuilderGroup = new QueryBuilder();
    $group = $queryBuilderGroup->select(['id', 'name'])->from('groups')->fetchAll();
    
    $queryBuilderUser = new QueryBuilder();
    $users = $queryBuilderUser->select(['id', 'email'])->from('users')->fetchAll();

    if (!$photo) {
      return redirect('/admin/photo');
    }

    return view('admin.photo.photo_form', ['photo' => $photo,'group_list' => $group,'user_list'=> $users,'update'=> true])->layout('admin');
  }

  public static function update()
  {
    $error = new Error;
    $id = (int)($_POST['id'] ?? 0);
    $group_id = (int)($_POST['group_id'] ?? 0);
    $user_id = (int)($_POST['user_id'] ?? 0);

    try {
      $photo = Photo::findOneById($id);
      if (!$photo) {
        $error->addError("Photo not found");
      }
    } catch (\Exception $e) {
      $error->addError("Invalid photo ID");
    }

    try {
      $group = Group::getOneById($group_id);
      if (!$group) {
        $error->addError("Selected group does not exist");
      }
    } catch (\Exception $e) {
      $error->addError("Invalid group selected");
    }

    try {
      $user = User::findOneById($user_id);
      if (!$user) {
        $error->addError("Selected user does not exist");
      }
    } catch (\Exception $e) {
      $error->addError("Invalid user selected");
    }

    if ($error->hasErrors()) {
      $queryBuilderGroup = new QueryBuilder();
      $groups = $queryBuilderGroup->select(['id', 'name'])->from('groups')->fetchAll();
      
      $queryBuilderUser = new QueryBuilder();
      $users = $queryBuilderUser->select(['id', 'email'])->from('users')->fetchAll();

      $tempPhoto = ['id' => $id,'file' => isset($photo) ? $photo->file : null,'group_id' => $group_id,'user_id' => $user_id];
      
      return view('admin.photo.photo_form', ['photo' => $tempPhoto,'group_list' => $groups,'user_list' => $users,'update'=> true,'errors' => $error->display()])->layout('admin');
    }

    if (isset($_FILES['photo']) && $_FILES['photo']['size'] > 0) {
      $imageController = new ImageController();
      $photo_file = $imageController->save($_FILES['photo'], ['subdir' => 'groups','group_id' => $group_id]);
      
      if ($photo_file) {
        if ($photo->file && file_exists($photo->file)) {
          $imageController->delete($photo->file);
        }
        $photo->file = $photo_file;
      } else {
        $error->addError("Failed to upload photo");
        return redirect('/admin/photo');
      }
    }

    $photo->group_id = $group_id;
    $photo->user_id = $user_id;

    $photo->update();
    return redirect('/admin/photo');
  }

  public static function addIndex()
  {
    $queryBuilderGroup = new QueryBuilder();
    $groups = $queryBuilderGroup->select(['id', 'name'])->from('groups')->fetchAll();
    
    $queryBuilderUser = new QueryBuilder();
    $users = $queryBuilderUser->select(['id', 'email'])->from('users')->fetchAll();

    return view('admin.photo.photo_form', ['group_list' => $groups,'user_list' => $users])->layout('admin');
  }

  public static function add()
  {
    $error = new Error;
    $group_id = (int)($_POST['group_id'] ?? 0);
    $user_id = (int)($_POST['user_id'] ?? 0);

    try {
      $group = Group::getOneById($group_id);
      if (!$group) {
        $error->addError("Selected group does not exist");
      }
    } catch (\Exception $e) {
      $error->addError("Invalid group selected");
    }

    try {
      $user = User::findOneById($user_id);
      if (!$user) {
        $error->addError("Selected user does not exist");
      }
    } catch (\Exception $e) {
      $error->addError("Invalid user selected");
    }

    if (!isset($_FILES['photo']) || $_FILES['photo']['size'] === 0) {
      $error->addError("Photo file is required");
    } else {
      $imageController = new ImageController();
      $photo_file = $imageController->save($_FILES['photo'], ['subdir' => 'groups','group_id' => $group_id]);
      
      if (!$photo_file) {
        $error->addError("Failed to upload photo");
      }
    }

    if ($error->hasErrors()) {
      $queryBuilderGroup = new QueryBuilder();
      $groups = $queryBuilderGroup->select(['id', 'name'])->from('groups')->fetchAll();
      
      $queryBuilderUser = new QueryBuilder();
      $users = $queryBuilderUser->select(['id', 'email'])->from('users')->fetchAll();

      $tempPhoto = ['id' => null,'file' => null,'group_id' => $group_id,'user_id' => $user_id];
      
      return view('admin.photo.photo_form', ['photo' => $tempPhoto,'group_list' => $groups,'user_list' => $users,'errors' => $error->display()])->layout('admin');
    }

    $photo = new Photo(null, $photo_file, $group_id, $user_id);
    $photo->createPhoto();
    
    return redirect('/admin/photo');
  }

}
