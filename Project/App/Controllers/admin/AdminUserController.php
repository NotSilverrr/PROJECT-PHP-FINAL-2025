<?php
namespace App\Controllers\admin;

use App\Models\User;
use App\Requests\LoginRequest;
use Core\QueryBuilder;

class AdminUserController
{
  public static function index()
  {
    $queryBuilder = new QueryBuilder();
    $users = $queryBuilder->select(['id', 'email', 'is_admin', 'profile_picture', 'created_at'])->from('users')->fetchAll();

    return view('admin.user.user', ['users' => $users])->layout('admin');
  }

  public static function delete()
  {
    $id = $_POST['id'];
    $queryBuilder = new QueryBuilder();
    $query = $queryBuilder->delete()->from('users')->where('id','=', $id)->execute();
    
    return redirect('/admin/user');
  }

}
