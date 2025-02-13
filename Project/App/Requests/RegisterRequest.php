<?php

namespace App\Requests;

class RegisterRequest
{
    public string $email;
    public string $profile_picture;
    public string $first_name;
    public string $last_name;
    public string $password;
    public string $password_check;

    public function __construct()
    {
        $this->email = strtolower(trim(htmlspecialchars($_POST["email"])));
        $this->profile_picture = trim(htmlspecialchars($_POST["profile_picture"]));
        $this->first_name = trim(htmlspecialchars($_POST["first_name"]));
        $this->last_name = trim(htmlspecialchars($_POST["last_name"]));
        $this->password = $_POST["password"];
        $this->password_check = $_POST["password_check"];
    }
}