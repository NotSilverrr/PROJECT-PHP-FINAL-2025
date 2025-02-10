<?php
namespace App\Controllers;

use App\Models\User;
use App\Requests\RegisterRequest;

class RegisterController
{
  public static function index()
  {
    return view('register.index')->layout('guest');
  }

  public static function post(): void
  {
    $request = new RegisterRequest();

    if($request->password !== $request->password_check){
      echo "Les mots de passe ne sont pas identiques";
      exit;
    }

    try {
      $user = new User(
        isadmin: false, 
        profile_picture: $request->profile_picture, 
        email: $request->email, 
        password: $request->password
      );
        if ($user->findOneByEmail($request->email)) {
            echo "Cet utilisateur existe deja";
            exit;
        }
        $user->createUser();
        header('Location: /register');
        echo "Utilisateur crée avec succès !";
        exit;
    } catch (\Exception $e) {
        echo $e->getMessage();
        exit;
    }
  }
}
