<?php 

namespace App\Requests;

class PhotoRequest {
    public array $file;
    public int $userID;
    public int $groupID;

    public function __construct()
    {
        $this->file = $_FILES['photo'];
        $this->userID = $_POST['user_id'];
        $this->groupID = $_POST['group_id'];
    }

}