<?php
namespace App\Services;

use App\Models\User;

class RegisterService
{

    public string $email;
    public string $password;
 
public function validate_email(){
    if (empty($this->email)) {
        return [false,"Email is required"];
    } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
        return [false,"Invalid email format"];
    } elseif (strlen($this->email) > 320) {
        return [false,"Email must be less than 320 characters"];
    }
    return [true,""];
}

public function validate_password(){
    if (empty($this->password)) {
        return [false,"Password is required"];
    } elseif (strlen($this->password) < 6) {
        return [false,"Password must be at least 6 characters long"];
    } elseif (strlen($this->password) > 50) {
        return [false,"Password must not be longer than 50 characters"];
    }
    return [true,""];
}

}
