<?php
namespace App\Controllers\admin;

use App\Models\Group;
use App\Models\User;
use Core\QueryBuilder;
use App\Controllers\ImageController;
use App\Services\GroupService;
use App\Requests\GroupRequest;
use App\Services\Auth;
use App\Services\ImageService;

class AdminGroupController
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
    $groups = Group::getAllGroup($_GET['g'] ?? "");

    return view('admin.group.group', ['groups' => $groups])->layout('admin');
  }

  public static function delete()
  {
    self::checkAdminAuth();
    
    $id = $_POST['id'];
    $group = Group::getOneById($id);
    $group->delete();    
    return redirect('/admin/group');
  }

  public static function updateIndex(int $id)
  {
    self::checkAdminAuth();
    
    $users = User::getAllUsers();

    $members = Group::getMembers($id);

    $group = Group::getOneById($id);
    $_SESSION['group_update'] = $group;

    $memberIds = array_column($members, 'id');
    $available_users = array_filter($users, function($user) use ($memberIds) {
        return !in_array($user->id, $memberIds);
    });


    if (!$group) {
      return redirect('/admin/group/update/'.$id);
    }

    return view('admin.group.group_form', ['user_list' => $users,'members' => $members,'available_users' => $available_users,'update' => true])->layout('admin');
  }

  public static function update()
  {
    self::checkAdminAuth();
    $request = new GroupRequest();
    $service = new GroupService($request);

    $id = (int)($_POST['id'] ?? 0);
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $owner_id = (int)($_POST['owner'] ?? 0);

    $error = $service->validate_name();
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    $error = $service->check_owner_exist();
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    $error = $service->validate_profile_picture();
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    try {
      $group = Group::getOneById($id);
      if (!$group) {
        $_SESSION['error'] = "Group not found";
      }
    } catch (\Exception $e) {
      $_SESSION['error'] = "Invalid group ID";
    }

    if(isset($_SESSION['error'])){
      $tempgroup = Group::getOneById($id);
      $_SESSION['group_update'] = $tempgroup;

      header("Location:/admin/group/update/".$id);
    }

    $group->name = $name;
    $group->ownerId = $owner_id;
    
    $uploadDir = "uploads/groups/". $group->id;
    $fileName = ImageService::uploadPhoto($request->profile_picture, $uploadDir);
    $error = $service->validate_profile_picture_save($fileName);
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    $pathToDelete = __DIR__ . '/../../..' . $group->profile_picture;
    if (file_exists($pathToDelete)) {
      ImageService::delete($group->profile_picture);

    }
    
    $group->profile_picture = $fileName;

    if(isset($_SESSION['error'])){
      $tempgroup = Group::getOneById($id);
      $_SESSION['group_update'] = $tempgroup;

      header("Location:/admin/group/update/".$id);
    }

    $group->update();

      header("Location:/admin/group");
  }

  public static function addIndex()
  {
    self::checkAdminAuth();
    
    $users = User::getAllUsers();

    return view('admin.group.group_form', ['user_list' => $users])->layout('admin');
  }

  public static function add()
  {
    self::checkAdminAuth();
    $request = new GroupRequest();
    $service = new GroupService($request);

    $error = $service->validate_name();
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    $error = $service->check_owner_exist();
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    $error = $service->validate_profile_picture();
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    $group = new Group(
      name: $request->name,
      profile_picture: null,
      ownerId: Auth::id()
    );

    if(isset($_SESSION['error'])){
      $_SESSION['group_update'] = $group;
      
      header("Location:/admin/group/add");
    }



    $group->createGroup();

    $uploadDir = "uploads/groups/". $group->id;
    $fileName = ImageService::uploadPhoto($request->profile_picture, $uploadDir);

    $error = $service->validate_profile_picture_save($fileName);
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }
    $group->profile_picture = $fileName;
    $group->update();

    if(isset($_SESSION['error'])){
      $_SESSION['group_update'] = $group;
      
      header("Location:/admin/group/add");
    }
    
    return redirect('/admin/group');
  }
}
