<?php
namespace App\Controllers\admin;

use App\Models\Group;
use Core\QueryBuilder;

class AdminGroupController
{
  public static function index()
  {
    $queryBuilder = new QueryBuilder();
    $groups = $queryBuilder
      ->select(['groups.id', 'groups.name', 'groups.profile_picture', 'users.email as owner', 'groups.created_at'])
      ->from('groups')
      ->join('users', 'groups.owner', '=', 'users.id')
      ->fetchAll();

    return view('admin.group.group', ['groups' => $groups])->layout('admin');
  }
  public static function delete()
  {
    $id = $_POST['id'];
    $queryBuilder = new QueryBuilder();
    $query = $queryBuilder->delete()->from('groups')->where('id',"=", $id)->execute();
    
    return redirect('/admin/group');
  }

  public static function updateIndex(int $id)
  {
    $queryBuilderGroup = new QueryBuilder();
    $group = $queryBuilderGroup->select(['id', 'name', 'profile_picture', 'owner'])->from('groups')->where('id', '=', $id)->fetch();
    
    $queryBuilderUser = new QueryBuilder();
    $users = $queryBuilderUser->select(['id', 'email'])->from('users')->fetchAll();

    if (!$group) {
      return redirect('/admin/group');
    }

    return view('admin.group.group_form', ['group' => $group,'user_list'=> $users])->layout('admin');
  }

  private static function handleProfilePicture($file, $groupId) {
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
      return null;
    }

    $uploadDir = dirname(dirname(dirname(__DIR__))) . '/uploads/groups/';
    $uploadDir = $uploadDir . (string)$groupId . '/';

    if (!file_exists($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }

    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    
    if (!in_array($fileExtension, $allowedExtensions)) {
      return null;
    }

    $filename = sprintf('%s.%s', uniqid(rand(), true), $fileExtension);
    $targetPath = $uploadDir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
      return null;
    }

    return $targetPath;
  }

  public static function update()
  {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $owner_id = $_POST['owner'];

    $group = Group::getOneById($id);
    $group->name = $name;
    $group->ownerId = $owner_id;

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['size'] > 0) {
      $filename = self::handleProfilePicture($_FILES['profile_picture'], $id);
      if ($filename) {
        if ($group->profile_picture && file_exists($group->profile_picture)) {
          unlink($group->profile_picture);
        }
        $group->profile_picture = $filename;
      }
    }

    $group->update();
    
    return redirect('/admin/group');
  }

  public static function addIndex()
  {
    $queryBuilder = new QueryBuilder();
    $users = $queryBuilder->select(['id', 'email'])->from('users')->fetchAll();

    return view('admin.group.group_form',['user_list' => $users])->layout('admin');
  }
  public static function add()
  {
    $name = $_POST['name'];
    $owner_id = $_POST['owner'];

    $group = new Group(null, $name, null, $owner_id);
    $group->createGroup();
    
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['size'] > 0) {
      $filename = self::handleProfilePicture($_FILES['profile_picture'], $group->id);
      if ($filename) {
        $group->profile_picture = $filename;
        $group->update();
      }
    }
    
    return redirect('/admin/group');
  }
}
