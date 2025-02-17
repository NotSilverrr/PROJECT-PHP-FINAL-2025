<?php
namespace App\Controllers\admin;

use App\Models\Group;
use Core\QueryBuilder;
use App\Controllers\ImageController;
use App\Services\GroupService;
use App\Requests\GroupRequest;
use App\Services\Auth;

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
    $queryBuilder = new QueryBuilder();
    $query = $queryBuilder->delete()->from('groups')->where('id',"=", $id)->execute();
    
    return redirect('/admin/group');
  }

  public static function updateIndex(int $id)
  {
    self::checkAdminAuth();
    
    $queryBuilderGroup = new QueryBuilder();
    $group = $queryBuilderGroup->select(['id', 'name', 'profile_picture', 'owner'])->from('groups')->where('id', '=', $id)->fetch();
    
    $queryBuilderUser = new QueryBuilder();
    $users = $queryBuilderUser->select(['id', 'email'])->from('users')->fetchAll();

    $members = Group::getMembers($id);

    $memberIds = array_column($members, 'id');
    $available_users = array_filter($users, function($user) use ($memberIds) {
        return !in_array($user['id'], $memberIds);
    });

    if (!$group) {
      return redirect('/admin/group/update/'.$id);
    }

    return view('admin.group.group_form', ['group' => $group,'user_list' => $users,'members' => $members,'available_users' => $available_users,'update' => true])->layout('admin');
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
      $queryBuilder = new QueryBuilder();
      $users = $queryBuilder->select(['id', 'email'])->from('users')->fetchAll();
      
      $tempGroup = ['id' => $id,'name' => $name,'owner' => $owner_id,'profile_picture' => isset($group) ? $group->profile_picture : null];
      $members = Group::getMembers($id);
      $memberIds = array_column($members, 'id');
      $available_users = array_filter($users, function($user) use ($memberIds) {
          return !in_array($user['id'], $memberIds);
      });
      
      return view('admin.group.group_form', ['user_list' => $users,'group' => $tempGroup,'members' => $members,'available_users' => $available_users,'update' => true])->layout('admin');
    }

    $group->name = $name;
    $group->ownerId = $owner_id;

    $imageController = new ImageController();
    $profile_picture = $imageController->save($_FILES['profile_picture'], [
      'subdir' => 'user_profile_picture'
    ]);
    $group->profile_picture = $profile_picture;
    $error = $service->validate_profile_picture_save($profile_picture);
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    if(isset($_SESSION['error'])){
      $queryBuilder = new QueryBuilder();
      $users = $queryBuilder->select(['id', 'email'])->from('users')->fetchAll();
      
      $tempGroup = ['id' => $id,'name' => $name,'owner' => $owner_id,'profile_picture' => isset($group) ? $group->profile_picture : null];
      $members = Group::getMembers($id);
      $memberIds = array_column($members, 'id');
      $available_users = array_filter($users, function($user) use ($memberIds) {
          return !in_array($user['id'], $memberIds);
      });
      
      return view('admin.group.group_form', ['user_list' => $users,'group' => $tempGroup,'members' => $members,'available_users' => $available_users,'update' => true])->layout('admin');
    }

    $group->update();

    return redirect('/admin/group');
  }

  public static function addIndex()
  {
    self::checkAdminAuth();
    
    $queryBuilder = new QueryBuilder();
    $users = $queryBuilder->select(['id', 'email'])->from('users')->fetchAll();

    return view('admin.group.group_form', ['user_list' => $users])->layout('admin');
  }

  public static function add()
  {
    self::checkAdminAuth();
    $request = new GroupRequest();
    $service = new GroupService($request);

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

    if(isset($_SESSION['error'])){
      $queryBuilder = new QueryBuilder();
      $users = $queryBuilder->select(['id', 'email'])->from('users')->fetchAll();
      
      $tempGroup = ['name' => $name,'owner' => $owner_id,'profile_picture' => null,'id' => null];
      
      return view('admin.group.group_form', ['user_list' => $users,'group' => $tempGroup])->layout('admin');
    }

    $imageController = new ImageController();
    $profile_picture = $imageController->save($_FILES['profile_picture'], [
      'subdir' => 'user_profile_picture'
    ]);
    
    $error = $service->validate_profile_picture_save($profile_picture);
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    if(isset($_SESSION['error'])){
      $queryBuilder = new QueryBuilder();
      $users = $queryBuilder->select(['id', 'email'])->from('users')->fetchAll();
      
      $tempGroup = ['name' => $name,'owner' => $owner_id,'profile_picture' => null,'id' => null];
      
      return view('admin.group.group_form', ['user_list' => $users,'group' => $tempGroup])->layout('admin');
    }

    $group = new Group(null, $name, null, $owner_id);
    $group->createGroup();
    
    return redirect('/admin/group');
  }
}
