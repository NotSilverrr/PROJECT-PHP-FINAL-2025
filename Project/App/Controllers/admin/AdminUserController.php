<?php
namespace App\Controllers\admin;

use App\Models\User;
use Core\QueryBuilder;
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

    $users = User::getAllUsers($_GET['u'] ?? "");

    return view('admin.user.user', ['users' => $users])->layout('admin');
  }

  public static function delete()
  {
    self::checkAdminAuth();
    
    $id = $_POST['id'];
    $user = User::findOneById($id);
    $user->delete();    
    
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

    $error = $service->validate_email();
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    $error = $service->validate_first_name();
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    $error = $service->validate_last_name();
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    if (!empty($_POST['password'])) {
      $error = $service->validate_password();
      if ($error !== null) {
        $_SESSION['error'] = $error;
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
      $error = $service->check_user_exist();
      if ($error !== null) {
        $_SESSION['error'] = $error;
      }
    }

    $error = $service->validate_profile_picture();
    if ($error !== null) {
      $_SESSION['error'] = $error;
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
    
    $error = $service->validate_profile_picture_save($profile_picture);
    if ($error !== null) {
      $_SESSION['error'] = $error;
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

    $error = $service->validate_email();
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    $error = $service->validate_first_name();
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    $error = $service->validate_last_name();
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    $error = $service->validate_password();
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    $error = $service->check_user_exist();
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    $error = $service->validate_profile_picture();
    if ($error !== null) {
      $_SESSION['error'] = $error;
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
    
    $error = $service->validate_profile_picture_save($profile_picture);
    if ($error !== null) {
      $_SESSION['error'] = $error;
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
