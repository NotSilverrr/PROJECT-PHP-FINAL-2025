<?php
namespace App\Services;

use App\Models\User;
use App\Models\Group;

class PhotoService
{

    public ?array $file;
    public int $userID;
    public int $groupID;
    

    public function __construct($request)
    {
        $this->file = $request->file ?? null;
        $this->userID = $request->userID;
        $this->groupID = $request->groupID;
    }

    public function check_user_exist(){
        $user = User::findOneById($this->userID);
        if (empty($user)) {
            return "The user does not exist";
        }
        return null;
    }

    public function check_group_exist(){
        $group = Group::getOneById($this->groupID);
        if (empty($group)) {
            return "The group does not exist";
        }
        return null;
    }

    public function validate_file(){
        if (!isset($this->file)) {
            return "File is required";
        } elseif ($this->file['size'] < 0){
            return "File is required";
        }
        return null;
    }
    public function validate_file_save($file){
        if (!$file) {
            return "Failed to upload file";
        }
        return null;
    }


}
