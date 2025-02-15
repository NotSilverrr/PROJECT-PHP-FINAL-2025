<?php
namespace App\Controllers\admin;

use App\Models\User;
use Core\QueryBuilder;
use Core\Error;
use App\Controllers\ImageController;

class AdminUserController
{
  public static function index()
  {
    $queryBuilder = new QueryBuilder();
    $users = $queryBuilder->select(['id', 'email','first_name','last_name', 'is_admin', 'profile_picture', 'created_at'])->from('users')->fetchAll();

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
    $user = $queryBuilder->select(['id', 'email','first_name','last_name', 'is_admin', 'profile_picture'])->from('users')->where('id', '=', $id)->fetch();
    
    if (!$user) {
      return redirect('/admin/user');
    }

    return view('admin.user.user_form', ['user' => $user,'update'=> true])->layout('admin');
  }

  public static function update()
  {
    $error = new Error;
    $id = (int)($_POST['id'] ?? 0);
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $first_name = htmlspecialchars(trim($_POST['first_name'] ?? ''));
    $last_name = htmlspecialchars(trim($_POST['last_name'] ?? ''));
    $password = trim($_POST['password'] ?? '');
    $is_admin = isset($_POST['is_admin']);

    if (empty($email)) {
      $error->addError("Email is required");
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error->addError("Invalid email format");
    } elseif (strlen($email) > 320) {
      $error->addError("Email must be less than 320 characters");
    }

    if (empty($first_name)) {
      $error->addError("First name is required");
    } elseif (strlen($first_name) > 100) {
      $error->addError("First name must be less than 100 characters");
    }

    if (empty($last_name)) {
      $error->addError("Last name is required");
    } elseif (strlen($last_name) > 100) {
      $error->addError("Last name must be less than 100 characters");
    }

    try {
      $user = User::findOneById($id);
      if (!$user) {
        $error->addError("User not found");
      }
    } catch (\Exception $e) {
      $error->addError("Invalid user ID");
    }

    if ($email !== $user->email) {
      try {
        $existingUser = User::findOneByEmail($email);
        if ($existingUser) {
          $error->addError("Email is already in use");
        }
      } catch (\Exception $e) {}
    }

    if ($error->hasErrors()) {
      $tempUser = ['id' => $id,'email' => $email,'first_name' => $first_name,'last_name' => $last_name,'is_admin' => $is_admin,'profile_picture' => isset($user) ? $user->profile_picture : null];
      
      return view('admin.user.user_form', ['user' => $tempUser,'update' => true,'errors' => $error->display()])->layout('admin');
    }
    
    $user->email = $email;
    $user->first_name = $first_name;
    $user->last_name = $last_name;
    $user->isadmin = $is_admin;

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['size'] > 0) {
      $imageController = new ImageController();
      $profile_picture = $imageController->save($_FILES['profile_picture'], ['subdir' => 'user_profile_picture']);
      
      if ($profile_picture) {
        if ($user->profile_picture) {
          $imageController->delete($user->profile_picture);
        }
        $user->profile_picture = $profile_picture;
      } else {
        $error->addError("Failed to upload profile picture");
      }
    }

    if ($password) {
      if (strlen($password) < 6) {
        $error->addError("Password must be at least 6 characters long");
        return redirect('/admin/user');
      }
      $user->password = $password;
    }
    
    $user->update();
    return redirect('/admin/user');
  }

  public static function addIndex()
  {
    return view('admin.user.user_form')->layout('admin');
  }

  public static function add()
  {
    $error = new Error;
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $first_name = htmlspecialchars(trim($_POST['first_name'] ?? ''));
    $last_name = htmlspecialchars(trim($_POST['last_name'] ?? ''));
    $password = trim($_POST['password'] ?? '');
    $is_admin = isset($_POST['is_admin']);

    $tempUser = ['id' => null,'email' => $email,'first_name' => $first_name,'last_name' => $last_name,'is_admin' => $is_admin,'profile_picture' => null];

    if (empty($email)) {
      $error->addError("Email is required");
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error->addError("Invalid email format");
    } elseif (strlen($email) > 320) {
      $error->addError("Email must be less than 320 characters");
    }

    if (empty($first_name)) {
      $error->addError("First name is required");
    } elseif (strlen($first_name) > 100) {
      $error->addError("First name must be less than 100 characters");
    }

    if (empty($last_name)) {
      $error->addError("Last name is required");
    } elseif (strlen($last_name) > 100) {
      $error->addError("Last name must be less than 100 characters");
    }

    if (empty($password)) {
      $error->addError("Password is required");
    } elseif (strlen($password) < 6) {
      $error->addError("Password must be at least 6 characters long");
    }

    try {
      $existingUser = User::findOneByEmail($email);
      if ($existingUser) {
        $error->addError("Email is already in use");
      }
    } catch (\Exception $e) {}

    if ($error->hasErrors()) {
      return view('admin.user.user_form', [
        'user' => $tempUser,
        'errors' => $error->display()
      ])->layout('admin');
    }

    $profile_picture = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['size'] > 0) {
      $imageController = new ImageController();
      $profile_picture = $imageController->save($_FILES['profile_picture'], [
        'subdir' => 'user_profile_picture'
      ]);
      
      if (!$profile_picture) {
        $error->addError("Failed to upload profile picture");
        return view('admin.user.user_form', [
          'user' => $tempUser,
          'errors' => $error->display()
        ])->layout('admin');
      }
    }

    $user = new User(null,$first_name,$last_name,$profile_picture,$is_admin,$email,$password);
    $user->createUser();
    
    return redirect('/admin/user');
  }
}
