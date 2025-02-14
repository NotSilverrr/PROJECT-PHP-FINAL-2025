<?php
namespace App\Controllers\admin;

use App\Models\Photo;
use Core\QueryBuilder;

class AdminPhotoController
{
  private static function handlePhotoUpload($file, $groupId) {
    if (!$file || $file['error'] !== UPLOAD_ERR_OK || !$groupId) {
      return null;
    }

    $uploadDir = dirname(dirname(dirname(__DIR__))) . '/uploads/groups/' . $groupId . '/';
    if (!file_exists($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }

    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    
    if (!in_array($fileExtension, $allowedExtensions)) {
      return null;
    }

    // Generate a UUID for the filename
    $filename = sprintf('%s.%s', uniqid(rand(), true), $fileExtension);
    $targetPath = $uploadDir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
      return null;
    }

    return $targetPath;
  }

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
    
    // Get the photo info before deleting
    $queryBuilder = new QueryBuilder();
    $photo = $queryBuilder->select(['file'])->from('photos')->where('id', '=', $id)->fetch();
    
    // Delete the file if it exists
    if ($photo && file_exists($photo['file'])) {
      unlink($photo['file']);
    }
    
    // Delete the database record
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

    return view('admin.photo.photo_form', ['photo' => $photo,'group_list' => $group,'user_list'=> $users])->layout('admin');
  }

  public static function update()
  {
    $id = $_POST['id'];
    $group_id = $_POST['group_id'];
    $user_id = $_POST['user_id'];

    // Get existing photo
    $photo = Photo::findOneById($id);
    $photo->group_id = $group_id;
    $photo->user_id = $user_id;

    // Handle new photo upload if provided
    if (isset($_FILES['photo']) && $_FILES['photo']['size'] > 0) {
      $filename = self::handlePhotoUpload($_FILES['photo'], $group_id);
      if ($filename) {
        // Delete old photo if it exists
        if ($photo->file && file_exists($photo->file)) {
          unlink($photo->file);
        }
        $photo->file = $filename;
      }
    }

    $photo->update();
    
    return redirect('/admin/photo');
  }

  public static function addIndex()
  {
    $queryBuilderGroup = new QueryBuilder();
    $group = $queryBuilderGroup->select(['id', 'name'])->from('groups')->fetchAll();
    
    $queryBuilderUser = new QueryBuilder();
    $users = $queryBuilderUser->select(['id', 'email'])->from('users')->fetchAll();

    return view('admin.photo.photo_form',['group_list' => $group,'user_list' => $users])->layout('admin');
  }

  public static function add()
  {
    $group_id = $_POST['group_id'];
    $user_id = $_POST['user_id'];

    // Handle photo upload
    $filename = null;
    if (isset($_FILES['photo'])) {
      $filename = self::handlePhotoUpload($_FILES['photo'], $group_id);
      if (!$filename) {
        // Handle error - redirect back with error message
        return redirect('/admin/photo/add');
      }
    }

    $photo = new Photo(null, $filename, $group_id, $user_id);
    $photo->createPhoto();
    
    return redirect('/admin/photo');
  }
}
