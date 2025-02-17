<?php
namespace App\Controllers\admin;

use App\Models\Group;
use App\Models\User;
use Core\QueryBuilder;
use Core\Error;
use App\Controllers\ImageController;
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
    
    $error = new Error;
    $id = (int)($_POST['id'] ?? 0);
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $owner_id = (int)($_POST['owner'] ?? 0);

    if (empty($name)) {
      $error->addError("Group name is required");
    } elseif (strlen($name) > 50) {
      $error->addError("Group name must be less than 50 characters");
    }

    try {
      $owner = User::findOneById($owner_id);
      if (!$owner) {
        $error->addError("Selected owner does not exist");
      }
    } catch (\Exception $e) {
      $error->addError("Invalid owner selected");
    }

    try {
      $group = Group::getOneById($id);
      if (!$group) {
        $error->addError("Group not found");
      }
    } catch (\Exception $e) {
      $error->addError("Invalid group ID");
    }

    if ($error->hasErrors()) {
      $queryBuilder = new QueryBuilder();
      $users = $queryBuilder->select(['id', 'email'])->from('users')->fetchAll();
      
      $tempGroup = ['id' => $id,'name' => $name,'owner' => $owner_id,'profile_picture' => isset($group) ? $group->profile_picture : null];
      
      $members = Group::getMembers($id);
      $memberIds = array_column($members, 'id');
      $available_users = array_filter($users, function($user) use ($memberIds) {
          return !in_array($user['id'], $memberIds);
      });
      
      return view('admin.group.group_form', ['user_list' => $users,'errors' => $error->display(),'group' => $tempGroup,'members' => $members,'available_users' => $available_users,'update' => true])->layout('admin');
    }

    $group->name = $name;
    $group->ownerId = $owner_id;

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['size'] > 0) {
      $imageController = new ImageController();
      $profile_picture = $imageController->save($_FILES['profile_picture'], ['subdir' => 'groups','group_id' => $id,'filename' => 'profile_picture','overwrite' => true]);
      
      if ($profile_picture) {
        if ($group->profile_picture && file_exists($group->profile_picture)) {
          $imageController->delete($group->profile_picture);
        }
        $group->profile_picture = $profile_picture;
      } else {
        $error->addError("Failed to upload profile picture");
      }
    }

    if ($error->hasErrors()) {
      return view('admin.group.group_form', ['errors' => $error->display(),'group' => $group])->layout('admin');
    }

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
    
    $error = new Error;
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $owner_id = (int)($_POST['owner'] ?? 0);

    if (empty($name)) {
      $error->addError("Group name is required");
    } elseif (strlen($name) > 50) {
      $error->addError("Group name must be less than 50 characters");
    }

    try {
      $owner = User::findOneById($owner_id);
      if (!$owner) {
        $error->addError("Selected owner does not exist");
      }
    } catch (\Exception $e) {
      $error->addError("Invalid owner selected");
    }

    if ($error->hasErrors()) {
      $queryBuilder = new QueryBuilder();
      $users = $queryBuilder->select(['id', 'email'])->from('users')->fetchAll();
      
      $tempGroup = ['name' => $name,'owner' => $owner_id,'profile_picture' => null,'id' => null];
      
      return view('admin.group.group_form', ['user_list' => $users,'errors' => $error->display(),'group' => $tempGroup])->layout('admin');
    }

    $group = new Group(null, $name, null, $owner_id);
    $group->createGroup();
    
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['size'] > 0) {
      $imageController = new ImageController();
      $profile_picture = $imageController->save($_FILES['profile_picture'], ['subdir' => 'groups','group_id' => $group->id,'filename' => 'profile_picture','overwrite' => true]);
      
      if ($profile_picture) {
        $group->profile_picture = $profile_picture;
        $group->update();
      } else {
        $error->addError("Failed to upload profile picture");
      }
    }
    
    return redirect('/admin/group');
  }
}
