<?php 
namespace App\Services;

class ResetPasswordService
{
    public string $password;
    public string $password_check;

    public function __construct($request)
    {
        $this->password = $request->password;
        $this->password_check = $request->password_check;
    }

    public function validate_password(){
        if (empty($this->password)) {
            return "Password is required";
        } elseif (strlen($this->password) < 6) {
            return "Password must be at least 6 characters long";
        } elseif (strlen($this->password) > 50) {
            return "Password must not be longer than 50 characters";
        }
        return null;
    }

    public function validate_password_check(){
        if (empty($this->password_check)) {
            return "Password is required";
        } elseif (strlen($this->password_check) < 6) {
            return "Password must be at least 6 characters long";
        } elseif (strlen($this->password_check) > 50) {
            return "Password must not be longer than 50 characters";
        } elseif ($this->password_check != $this->password) {
            return "Password must be identical";
        }
        return null;
    }
}