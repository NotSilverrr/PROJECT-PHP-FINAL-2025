<?php

namespace App\Models;

use PDO;

class User
{
  public int $id;
  public string $created_at;
  public string $updated_at;

  public function __construct(
    public bool $isadmin,
    public string $profile_picture,
    public string $email,
    public string $password,
    
  ) {
    $this->id = 0;
    $this->created_at = date('Y-m-d H:i:s');
    $this->updated_at = date('Y-m-d H:i:s');
  }

  public static function findOneByEmail(string $email): User|null
  {
    $databaseConnection = new PDO(
      "mysql:host=mariadb;dbname=database",
      "user",
      "password"
    );

    $getUserQuery = $databaseConnection->prepare("SELECT id, is_admin, profile_picture, email, password , created_at, updated_at FROM users WHERE email = :email");

    $getUserQuery->execute([
      "email" => $email
    ]);

    $user = $getUserQuery->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        return null;
    }
    
    return new User($user["is_admin"], $user["profile_picture"], $user["email"], $user["password"], $user["created_at"], $user["updated_at"]);
  }

  public function createUser()
  {
    $databaseConnection = new PDO(
      "mysql:host=mariadb;dbname=database",
      "user",
      "password"
    );

    $getUserQuery = $databaseConnection->prepare("INSERT INTO users (profile_picture, email, password, is_admin, created_at) VALUES (:profile_picture, :email, :password, :isadmin, :created_at)");

    $getUserQuery->execute([
      "email" => $this->email,
      "isadmin" => (int)$this->isadmin,
      "profile_picture" => $this->profile_picture,
      "password" => password_hash($this->password, PASSWORD_DEFAULT),
      "created_at" => $this->created_at
    ]);
  }
  public function isValidPassword(string $password): bool
  {
    return password_verify($password, $this->password);
  }

}
