<?php
namespace App\Controllers;

use App\Models\User;
use App\Requests\LoginRequest;
use App\Services\LoginService;


class LoginController
{
  public static function index()
  {
    startSession();
    return view('login.index')->layout('guest');
  }

  public static function post()
  {
    startSession();
    $request = new LoginRequest();
    $service = new LoginService($request);
    $user = User::findOneByEmail($request->email);


    $error = $service->check_user_login();
    if ($error !== null) {
      $_SESSION['error'] = $error;
    }

    if(isset($_SESSION['error'])){
      $tempemail = $request->email ?? '';
      $_SESSION['login_email'] = $tempemail;
      return view('login.index')->layout('guest');
    }

    startSession();
    $_SESSION['user_id'] = $user->id;
    $_SESSION['login'] = 1;
    $_SESSION['isadmin'] = $user->isadmin;
    header('Location: /group');
    exit;
  }

  public static function delete(): void
  {
    startSession();
    session_destroy();
    header('Location: /');
    exit;
  }

}
