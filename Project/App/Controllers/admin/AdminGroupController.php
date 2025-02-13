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
      ->select(['id', 'name', 'profile_picture', 'owner', 'created_at'])
      ->from('groups')
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
  public static function update()
  {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $profile_picture = $_POST['profile_picture'];
    $owner_id = $_POST['owner'];

    $group = new Group($id, $name, $profile_picture, $owner_id);
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
    $profile_picture = $_POST['profile_picture'];
    $owner_id = $_POST['owner'];

    $group = new Group(null, $name, $profile_picture, $owner_id, );
    $group->createGroup();
    
    return redirect('/admin/group');
  }
}
