<?php
namespace App\Controllers\admin;

use App\Models\User;
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
    $user = $queryBuilder->select(['id', 'email', 'is_admin', 'profile_picture'])->from('users')->where('id', '=', $id)->fetch();
    
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
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;
    $profile_picture = $_POST['profile_picture'];

    $user = new User($id,$first_name, $last_name, $profile_picture, $is_admin, $email, $password);
    $user->update();
    
    return redirect('/admin/user');
  }

  public static function addIndex()
  {
    return view('admin.user.user_form')->layout('admin');
  }
  public static function add()
  {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;
    $profile_picture = $_POST['profile_picture'];

    $user = new User(null, $is_admin, $profile_picture, $email, $password);
    $user->createUser();
    
    return redirect('/admin/user');
  }
}
