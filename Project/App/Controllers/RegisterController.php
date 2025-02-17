<?php
namespace App\Controllers;

use App\Models\User;
use App\Requests\RegisterRequest;
use App\Controllers\ImageController;
use App\Services\ImageService;
use Core\Error;

class RegisterController
{
  public static function index()
  {
    return view('register.index')->layout('guest');
  }

  public static function post()
  {
    $error = new Error;
    $request = new RegisterRequest();

    $email = htmlspecialchars(trim($request->email ?? ''));
    $first_name = htmlspecialchars(trim($request->first_name ?? ''));
    $last_name = htmlspecialchars(trim($request->last_name ?? ''));
    $password = trim($request->password ?? '');
    $password_check = trim($request->password_check ?? '');

    $tempUser = [
      'id' => null,
      'email' => $email,
      'first_name' => $first_name,
      'last_name' => $last_name,
      'is_admin' => false,
      'profile_picture' => null
    ];

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

    if ($password !== $password_check) {
      $error->addError("Passwords do not match");
    }

    try {
      $existingUser = User::findOneByEmail($email);
      if ($existingUser) {
        $error->addError("Email is already in use");
      }
    } catch (\Exception $e) {
      $error->addError("An error occurred while checking email availability");
    }

    if ($error->hasErrors()) {
      return view('register.index', [
        'user' => $tempUser,
        'errors' => $error->display()
      ]);
    }

    $profile_picture = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['size'] > 0) {
      // $imageController = new ImageController();
      $uploadDir = "uploads/user_profile_picture";
      $profile_picture = ImageService::uploadPhoto($_FILES['profile_picture'], $uploadDir);
      // $profile_picture = $imageController->save($_FILES['profile_picture'], [
      //   'subdir' => 'user_profile_picture'
      // ]);
      
      if (!$profile_picture) {
        $error->addError("Failed to upload profile picture");
        return view('register.index', [
          'user' => $tempUser,
          'errors' => $error->display()
        ]);
      }
    }

    try {
      $user = new User(
        first_name: $first_name,
        last_name: $last_name,
        profile_picture: $profile_picture,
        isadmin: false,
        email: $email,
        password: $password
      );
      $user->createUser();
      
      header('Location: /login');
      exit;
    } catch (\Exception $e) {
      $error->addError($e->getMessage());
      print_r($e->getMessage());
      die();
      return view('register.index', [
        'user' => $tempUser,
        'errors' => $error->display()
      ]);
    }
  }
}
