<?php
namespace App\Controllers;

use App\Models\User;
use App\Requests\LoginRequest;


class LoginController
{
  public static function index()
  {
    return view('login.index')->layout('guest');
  }

  public static function post(): void
  {
    $request = new LoginRequest();
    $user = User::findOneByEmail($request->email);

    if (!$user) {
      echo "L'adresse email ou le mot de passe sont incorrects.";
      die();
    }

    if (!$user->isValidPassword($request->password)) {
      echo "L'adresse email ou le mot de passe sont incorrects.";
      die();
    }

    session_start();
    $_SESSION['user_id'] = $user->id;
    $_SESSION['login'] = 1;
    header('Location: /group');
  }

  public static function delete(): void
  {
    session_start();
    session_destroy();
    header('Location: /test');
    exit;
  }
}
