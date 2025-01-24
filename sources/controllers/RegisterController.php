<?php
namespace App\Controllers;

class RegisterController
{
  public static function index(): void
  {
    require_once __DIR__ . "/../views/register/index.php";
  }
}
