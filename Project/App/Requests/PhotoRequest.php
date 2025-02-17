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

    public function validate(): bool
    {
        return isset($this->file['name']) &&
               in_array(mime_content_type($this->file['tmp_name']), ['image/jpeg', 'image/png']) &&
               $this->file['size'] < 5 * 1024 * 1024; // Max 5MB
    }
}