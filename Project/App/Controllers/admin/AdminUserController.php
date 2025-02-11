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

  public static function updateIndex(int $id)
  {
    $queryBuilder = new QueryBuilder();
    $user = $queryBuilder->select(['id', 'email', 'is_admin', 'profile_picture', 'created_at'])->from('users')->where('id', '=', $id)->fetch();
    
    if (!$user) {
      return redirect('/admin/user');
    }

    return view('admin.user.user_form', ['user' => $user])->layout('admin');
  }
  public static function update()
  {
    $id = $_POST['id'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;
    $profile_picture = $_POST['profile_picture'];

    $user = new User($id, $is_admin, $profile_picture, $email, $password);
    $user->update();
    
    return redirect('/admin/user');
  }
}
