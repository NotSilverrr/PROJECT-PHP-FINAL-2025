<?php

namespace App\Requests;

class ResetPasswordRequest {

    public $password;
    public $password_check;
    public $token;

    public function __construct()
    {
        $this->password = htmlspecialchars($_POST['password']);
        $this->password_check = htmlspecialchars($_POST['password_check']);
        $this->token = htmlspecialchars($_POST['token']);
    }
}