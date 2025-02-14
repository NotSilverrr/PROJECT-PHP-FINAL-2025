<?php 
namespace App\Requests;

class MemberRequest {
  public int $userId;
  public function __construct()
  {
    $this->userId = $_POST["user_id"];
  }
}