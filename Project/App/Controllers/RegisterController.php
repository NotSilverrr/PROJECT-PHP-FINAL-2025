<?php
namespace App\Controllers;

use App\Models\User;
use App\Requests\RegisterRequest;
use App\Services\RegisterService;
use App\Controllers\ImageController;
use App\Services\ImageService;
use Core\Error;

class RegisterController
{
  public static function index()
  {
    startSession();
    return view('register.index')->layout('guest');
  }

  public static function post()
  {
    startSession();
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

    $error = $service->validate_password_check();
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    $email = htmlspecialchars(trim($request->email ?? ''));
    $first_name = htmlspecialchars(trim($request->first_name ?? ''));
    $last_name = htmlspecialchars(trim($request->last_name ?? ''));
    $password = trim($request->password ?? '');
    $password_check = trim($request->password_check ?? '');


    $error = $service->check_user_exist();
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    $error = $service->validate_profile_picture();
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    $user = new User(
      first_name: $first_name,
      last_name: $last_name,
      profile_picture: null,
      isadmin: false,
      email: $email,
      password: $password
    );
    $_SESSION['create_user'] = $user;
    
    if(isset($_SESSION['error'])){
      $tempUser = ['id' => null,'email' => $email,'first_name' => $first_name,'last_name' => $last_name,'password' => $password,'password_check' => $password_check,'profile_picture' => null];
      return view('register.index', ['user' => $tempUser])->layout('guest');
    }

    if(isset($_SESSION['error'])){
      $tempUser = ['id' => null,'email' => $email,'first_name' => $first_name,'last_name' => $last_name,'password' => $password,'password_check' => $password_check,'profile_picture' => null];
      return view('register.index', ['user' => $tempUser])->layout('guest');
    }
  

    try {
      $user->createUser();
      
      $uploadDir = "uploads/user_profile_picture/";
      $fileName = ImageService::uploadPhoto($request->profile_picture, $uploadDir);

      $error = $service->validate_profile_picture_save($fileName);
      if ($error !== null) {
          throw new \Exception($error);
      }
      $user->profile_picture = $fileName;
      $user->update();
      
      header('Location: /login');
      exit;
    } catch (\Exception $e) {
      $_SESSION['error'] = $e->getMessage();
      if(isset($_SESSION['error'])){
        return view('register.index')->layout('guest');
      }
    }
  }
}
