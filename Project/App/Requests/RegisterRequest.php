<?php

namespace App\Requests;


class RegisterRequest
{
    public string $email;
    public string $first_name;
    public string $last_name;
    public ?array $profile_picture;
    public string $password;
    public string $password_check;

    public function __construct()
    {
        $this->email = trim(htmlspecialchars($_POST["email"]));
        $this->first_name = trim(htmlspecialchars($_POST["first_name"]));
        $this->last_name = trim(htmlspecialchars($_POST["last_name"]));
        $this->profile_picture = $_FILES["profile_picture"] ?? null;
        $this->password = trim($_POST["password"]);
        $this->password_check = $_POST["password_check"] ?? "";
    }
}