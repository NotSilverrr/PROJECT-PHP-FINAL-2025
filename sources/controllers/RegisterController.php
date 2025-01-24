<?php
require_once __DIR__ . "/../models/User.php";

class RegisterController
{
  public static function index(): void
  {
    require_once __DIR__ . "/../views/register/index.php";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $email = $_POST['email'] ?? '';
      $profile_picture = $_POST['profile_picture'] ?? '';
      $password = $_POST['password'] ?? '';
      $password_check = $_POST['password'] ?? '';

      if($password !== $password_check){
        $view->addData('error', "Les mots de passe ne sont pas identiques");
      }

        try {
            $user = new User(false, $profile_picture, $email, $password);
            if ($user->findOneByEmail($email)) {
                $view->addData('error', "Cet utilisateur existe deja");
                exit;
            }
            //ajouter l'user
            
            header('Location: /login');
            exit;
        } catch (\Exception $e) {
            $view->addData('error', $e->getMessage());
        }
    }
  }
}
