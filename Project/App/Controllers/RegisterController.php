<?php
namespace App\Controllers;

use App\Models\User;
use App\Requests\RegisterRequest;

class RegisterController
{
  public static function index()
  {
    require_once __DIR__ . "/../views/register/index.php";
  }

  public static function post(): void
  {
    $request = new RegisterRequest();

    if($request->password !== $request->password_check){
      echo "Les mots de passe ne sont pas identiques";
      exit;
    }

    try {
        $user = new User(false, $request->profile_picture, $request->email, $request->password);
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
