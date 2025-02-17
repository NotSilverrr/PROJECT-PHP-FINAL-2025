<?php

namespace App\Requests;

class GroupRequest {
  public string $name;
  public array $profile_picture;
  public int $ownerID;
	public function __construct()
  {
    $this->name = htmlspecialchars($_POST['name']);
    $this->profile_picture = $_FILES['profile_picture'];
    $this->ownerID = (int)$_POST['owner'];
  }
}