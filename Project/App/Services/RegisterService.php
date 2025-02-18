<?php
namespace App\Services;

use App\Models\User;

class RegisterService
{

    public string $email;
    public string $first_name;
    public string $last_name;
    public ?array $profile_picture;
    public string $password;
    public string $password_check;

    public function __construct($request)
    {
        $this->email = $request->email;
        $this->first_name = $request->first_name;
        $this->last_name = $request->last_name;
        $this->profile_picture = $request->profile_picture ?? null;
        $this->password = $request->password;
        $this->password_check = $request->password_check;
    }
    
    public function validate_email(){
        if (empty($this->email)) {
            return "Email is required";
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format";
        } elseif (strlen($this->email) > 320) {
            return "Email must be less than 320 characters";
        }
        return null;
    }

    public function validate_first_name(){
        if (empty($this->first_name)) {
            return "First name is required";
        } elseif (strlen($this->first_name) > 100) {
            return "First name must be less than 100 characters";
        }
        return null;
    }
    public function validate_last_name(){
        if (empty($this->last_name)) {
            return "Last name is required";
        } elseif (strlen($this->last_name) > 100) {
            return "Last name must be less than 100 characters";
        }
        return null;
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

    public function validate_profile_picture(){
        if (!isset($this->profile_picture)) {
            return "Profile picture is required";
        } elseif ($this->profile_picture['size'] < 0){
            return "Profile picture is required";
        }
        return null;
    }
    public function validate_profile_picture_save($profile_picture){
        if (!$profile_picture) {
            return "Failed to upload profile picture";
        }
        return null;
    }

    public function check_user_exist(){
        $existingUser = User::findOneByEmail($this->email);
        if (!empty($existingUser)) {
            return "Email is already in use";
        }
        return null;
    }
}
