<?php
namespace App\Controllers\admin;

use App\Models\User;
use Core\QueryBuilder;
use Core\Error;
use App\Controllers\ImageController;
use App\Services\Auth;
use App\Services\RegisterService;
use App\Requests\RegisterRequest;

class AdminUserController
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
    
    $queryBuilder = new QueryBuilder();
    $users = $queryBuilder->select(['id', 'email','first_name','last_name', 'is_admin', 'profile_picture', 'created_at'])->from('users')->fetchAll();

    return view('admin.user.user', ['users' => $users])->layout('admin');
  }

  public static function delete()
  {
    self::checkAdminAuth();
    
    $id = $_POST['id'];
    $queryBuilder = new QueryBuilder();
    $query = $queryBuilder->delete()->from('users')->where('id','=', $id)->execute();
    
    return redirect('/admin/user');
  }

  public static function updateIndex(int $id)
  {
    self::checkAdminAuth();
    
    $queryBuilder = new QueryBuilder();
    $user = $queryBuilder->select(['id', 'email','first_name','last_name', 'is_admin', 'profile_picture'])->from('users')->where('id', '=', $id)->fetch();
    
    if (!$user) {
      return redirect('/admin/user');
    }

    return view('admin.user.user_form', ['user' => $user,'update'=> true])->layout('admin');
  }

  public static function update()
  {
    self::checkAdminAuth();
    unset($_SESSION['error']);
    $request = new RegisterRequest();
    $service = new RegisterService($request);

    $id = (int)($_POST['id'] ?? 0);
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $first_name = htmlspecialchars(trim($_POST['first_name'] ?? ''));
    $last_name = htmlspecialchars(trim($_POST['last_name'] ?? ''));
    $password = trim($_POST['password'] ?? '');
    $is_admin = isset($_POST['is_admin']);

    if (!$service->validate_email()[0]) {
      $_SESSION['error'] = $service->validate_email()[1];
    }

    if (!$service->validate_first_name()[0]) {
      $_SESSION['error'] = $service->validate_first_name()[1];
    }

    if (!$service->validate_last_name()[0]) {
      $_SESSION['error'] = $service->validate_last_name()[1];
    }

    if (!empty($_POST['password'])) {
      if (!$service->validate_password()[0]) {
        $_SESSION['error'] = $service->validate_password()[1];
      }
    }

    try {
      $user = User::findOneById($id);
      if (!$user) {
        $_SESSION['error'] = "User not found";
      }
    } catch (\Exception $e) {
      $_SESSION['error'] = "Invalid user ID";
    }

    if ($email !== $user->email) {
      if (!$service->check_user_exist()[0]) {
        $_SESSION['error'] = $service->check_user_exist()[1];
      }
    }

    if (!$service->validate_profile_picture()[0]) {
      $_SESSION['error'] = $service->validate_profile_picture()[1];
    }

    if(isset($_SESSION['error'])){
      $tempUser = ['id' => $id,'email' => $email,'first_name' => $first_name,'last_name' => $last_name,'is_admin' => $is_admin,'profile_picture' => isset($user) ? $user->profile_picture : null];
      return view('admin.user.user_form', ['user' => $tempUser,'update' => true])->layout('admin');
    }
    
    $user->email = $email;
    $user->first_name = $first_name;
    $user->last_name = $last_name;
    $user->isadmin = $is_admin;

    $imageController = new ImageController();
    $profile_picture = $imageController->save($_FILES['profile_picture'], [
      'subdir' => 'user_profile_picture'
    ]);
    
    if (!$service->validate_profile_picture_save($profile_picture)[0]) {
      $_SESSION['error'] = $service->validate_profile_picture_save($profile_picture)[1];
    }

    $user->profile_picture = $profile_picture;


    if(isset($_SESSION['error'])){
      $tempUser = ['id' => $id,'email' => $email,'first_name' => $first_name,'last_name' => $last_name,'is_admin' => $is_admin,'profile_picture' => isset($user) ? $user->profile_picture : null];
      return view('admin.user.user_form', ['user' => $tempUser,'update' => true])->layout('admin');
    }

    
    $user->update();
    return redirect('/admin/user');
  }

  public static function addIndex()
  {
    self::checkAdminAuth();
    
    return view('admin.user.user_form')->layout('admin');
  }

  public static function add()
  {
    self::checkAdminAuth();
    unset($_SESSION['error']);
    $request = new RegisterRequest();
    $service = new RegisterService($request);

    if (!$service->validate_email()[0]) {
      $_SESSION['error'] = $service->validate_email()[1];
    }

    if (!$service->validate_first_name()[0]) {
      $_SESSION['error'] = $service->validate_first_name()[1];
    }

    if (!$service->validate_last_name()[0]) {
      $_SESSION['error'] = $service->validate_last_name()[1];
    }

    if (!$service->validate_password()[0]) {
      $_SESSION['error'] = $service->validate_password()[1];
    }

    if (!$service->check_user_exist()[0]) {
      $_SESSION['error'] = $service->check_user_exist()[1];
    }

    if (!$service->validate_profile_picture()[0]) {
      $_SESSION['error'] = $service->validate_profile_picture()[1];
    }
    
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $first_name = htmlspecialchars(trim($_POST['first_name'] ?? ''));
    $last_name = htmlspecialchars(trim($_POST['last_name'] ?? ''));
    $password = trim($_POST['password'] ?? '');
    $is_admin = isset($_POST['is_admin']);


    if(isset($_SESSION['error'])){
      $tempUser = ['id' => null,'email' => $email,'first_name' => $first_name,'last_name' => $last_name,'is_admin' => $is_admin,'profile_picture' => null];
      return view('admin.user.user_form', ['user' => $tempUser])->layout('admin');
    }

    $imageController = new ImageController();
    $profile_picture = $imageController->save($_FILES['profile_picture'], [
      'subdir' => 'user_profile_picture'
    ]);
    
    if (!$service->validate_profile_picture_save($profile_picture)[0]) {
      $_SESSION['error'] = $service->validate_profile_picture_save($profile_picture)[1];
    }

    if(isset($_SESSION['error'])){
      $tempUser = ['id' => null,'email' => $email,'first_name' => $first_name,'last_name' => $last_name,'is_admin' => $is_admin,'profile_picture' => null];
      return view('admin.user.user_form', ['user' => $tempUser])->layout('admin');
    }

    $user = new User(null,$first_name,$last_name,$profile_picture,$is_admin,$email,$password);
    $user->createUser();
    
    return redirect('/admin/user');
  }
}
