<?php
namespace App\Controllers\admin;

use App\Models\Group;
use App\Models\User;
use App\Models\Photo;
use Core\QueryBuilder;
use App\Controllers\ImageController;
use App\Services\Auth;
use App\Services\PhotoService;
use App\Requests\PhotoRequest;

class AdminPhotoController
{
  private static function checkAdminAuth()
  {
    if (!Auth::check() || !Auth::isadmin()) {
      header('Location: /login');
      exit;
    }
  }

  public static function index()
  {
    self::checkAdminAuth();
    
    $photos = Photo::getAllPhoto();

    return view('admin.photo.photo', data: ['photos' => $photos])->layout('admin');
  }

  public static function delete()
  {
    self::checkAdminAuth();
    
    $id = $_POST['id'];
    $photo = Photo::findOneById($id);
    if ($photo && file_exists($photo->file)) {
      unlink($photo->file);
    }
    $photo->deletePhoto();    
    
    return redirect('/admin/photo');
  }

  public static function updateIndex(int $id)
  {
    self::checkAdminAuth();
    
    $queryBuilderPhoto = new QueryBuilder();
    $photo = $queryBuilderPhoto->select(['id', 'file', 'group_id', 'user_id'])->from('photos')->where('id', '=', $id)->fetch();

    $groups = Group::getAllGroup();
    $users = User::getAllUsers();

    if (!$photo) {
      return redirect('/admin/photo');
    }

    return view('admin.photo.photo_form', ['photo' => $photo,'group_list' => $groups,'user_list'=> $users,'update'=> true])->layout('admin');
  }

  public static function update()
  {
    self::checkAdminAuth();
    
    $request = new PhotoRequest();
    $service = new PhotoService($request);

    $id = (int)($_POST['id'] ?? 0);
    $group_id = (int)($_POST['group_id'] ?? 0);
    $user_id = (int)($_POST['user_id'] ?? 0);

    try {
      $photo = Photo::findOneById($id);
      if (!$photo) {
        $_SESSION['error'] = "Photo not found";
      }
    } catch (\Exception $e) {
      $_SESSION['error'] = "Invalid photo ID";
    }

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
      $groups = Group::getAllGroup();
      $users = User::getAllUsers();

      $tempPhoto = ['id' => $id,'file' => isset($photo) ? $photo->file : null,'group_id' => $group_id,'user_id' => $user_id];
      
      return view('admin.photo.photo_form', ['photo' => $tempPhoto,'group_list' => $groups,'user_list' => $users,'update'=> true])->layout('admin');
    }

    $imageController = new ImageController();
    $file = $imageController->save($_FILES['photo'], [
      'subdir' => 'user_profile_picture'
    ]);
    
    $error = $service->validate_file_save($file);
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    $photo->group_id = $group_id;
    $photo->user_id = $user_id;

    $photo->update();
    return redirect('/admin/photo');
  }

  public static function addIndex()
  {
    self::checkAdminAuth();
    
    $groups = Group::getAllGroup();
    $users = User::getAllUsers();

    return view('admin.photo.photo_form', ['group_list' => $groups,'user_list' => $users])->layout('admin');
  }

  public static function add()
  {
    self::checkAdminAuth();
    $request = new PhotoRequest();
    $service = new PhotoService($request);

    $group_id = (int)($_POST['group_id'] ?? 0);
    $user_id = (int)($_POST['user_id'] ?? 0);

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

    $imageController = new ImageController();
    $file = $imageController->save($_FILES['photo'], [
      'subdir' => 'user_profile_picture'
    ]);
    
    $error = $service->validate_file_save($file);
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    if (isset($_SESSION['error'])) {
      $groups = Group::getAllGroup();
      $users = User::getAllUsers();

      $tempPhoto = ['id' => null,'file' => null,'group_id' => $group_id,'user_id' => $user_id];
      
      return view('admin.photo.photo_form', ['photo' => $tempPhoto,'group_list' => $groups,'user_list' => $users])->layout('admin');
    }

    $photo = new Photo(null, $file, $group_id, $user_id);
    $photo->createPhoto();
    
    return redirect('/admin/photo');
  }
}
