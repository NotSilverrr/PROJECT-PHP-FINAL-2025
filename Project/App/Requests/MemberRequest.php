<?php 
namespace App\Requests;

class MemberRequest {
  public int $userId;
  public ?bool $readOnly = false;
  public function __construct()
  {
    $this->userId = $_POST["user_id"];
    $this->readOnly = $_POST["read_only"] ?? false;
  }
}