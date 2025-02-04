<?php

namespace App\Requests;

class RegisterRequest
{
    public string $email;
    public string $profile_picture;
    public string $password;
    public string $password_check;

    public function __construct()
    {
        $this->email = strtolower(trim(htmlspecialchars($_POST["email"])));
        $this->profile_picture = trim(htmlspecialchars($_POST["profile_picture"]));
        $this->password = $_POST["password"];
        $this->password_check = $_POST["password_check"];
    }
}