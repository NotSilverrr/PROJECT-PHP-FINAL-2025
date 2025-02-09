<?php
namespace App\Controllers\admin;

use App\Models\User;
use App\Requests\LoginRequest;
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
    $query = $queryBuilder->delete()->from('groups')->where('id', $id)->execute();
    
    return redirect('/admin/group');
  }
}
