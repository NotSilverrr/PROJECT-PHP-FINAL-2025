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
        return [false,"Email is required"];
    } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
        return [false,"Invalid email format"];
    } elseif (strlen($this->email) > 320) {
        return [false,"Email must be less than 320 characters"];
    }
    return [true,""];
}

public function validate_first_name(){
    if (empty($this->first_name)) {
        return [false,"First name is required"];
    } elseif (strlen($this->first_name) > 100) {
        return [false,"First name must be less than 100 characters"];
    }
    return [true,""];
}
public function validate_last_name(){
    if (empty($this->last_name)) {
        return [false,"Last name is required"];
    } elseif (strlen($this->last_name) > 100) {
        return [false,"Last name must be less than 100 characters"];
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

public function validate_password_check(){
    if (empty($this->password_check)) {
        return [false,"Password is required"];
    } elseif (strlen($this->password_check) < 6) {
        return [false,"Password must be at least 6 characters long"];
    } elseif (strlen($this->password_check) > 50) {
        return [false,"Password must not be longer than 50 characters"];
    } elseif ($this->password_check != $this->password_check) {
        return [false,"Password must be identical"];
    }
    return [true,""];
}

public function validate_profile_picture(){
    if (!isset($this->profile_picture)) {
        return [false,"Profile picture is required"];
    } elseif ($this->profile_picture['size'] < 0){
        return [false,"Profile picture is required"];
    }
    return [true,""];
}
public function validate_profile_picture_save($profile_picture){
    if (!$profile_picture) {
        return [false,"Failed to upload profile picture"];
    }
    return [true,""];
}

public function check_user_exist(){
    $existingUser = User::findOneByEmail($this->email);
    if (!empty($existingUser)) {
        return [false,"Email is already in use"];
    }

    return [true,""];
}
}
