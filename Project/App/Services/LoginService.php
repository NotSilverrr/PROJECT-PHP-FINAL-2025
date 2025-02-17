<?php
namespace App\Services;

use App\Models\User;

class LoginService
{

    public string $email;
    public string $password;
    

    public function __construct($request)
    {
        $this->email = $request->email;
        $this->password = $request->password;
    }
    public function check_user_login(){
        $user = User::findOneByEmail($this->email);
        if (empty($user)) {
            return "No user found with this email";
        }
        if (!$user->isValidPassword($this->password)) {
            return "Email or password incorrect.";
        }
        return null;
    }
}
