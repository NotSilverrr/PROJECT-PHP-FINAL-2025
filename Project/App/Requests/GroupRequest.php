<?php

namespace App\Requests;

class GroupRequest {
  public string $name;
  public string $profile_picture;
	public function __construct()
  {
    $this->name = $_POST['name'];
    $this->profile_picture = $_POST['profile_picture'];
  }
}