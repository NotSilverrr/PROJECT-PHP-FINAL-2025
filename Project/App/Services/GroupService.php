<?php
namespace App\Services;

use App\Models\User;

class GroupService
{

    public string $name;
    public ?array $profile_picture;
    public int $ownerID;
    

    public function __construct($request)
    {
        $this->name = $request->name;
        $this->profile_picture = $request->profile_picture ?? null;
        $this->ownerID = $request->ownerID;
    }
    public function validate_name(){
        if (empty($this->name)) {
            return "Group name is required";
        } elseif (strlen($this->name) > 50) {
            return "Name must be less than 50 characters";
        }
        return null;
    }

    public function check_owner_exist(){
        $user = User::findOneById($this->ownerID);
        if (empty($user)) {
            return "The owner does not exist";
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


}
