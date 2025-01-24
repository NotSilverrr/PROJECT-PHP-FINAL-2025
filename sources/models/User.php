<?php

class User
{
  public int $id;
  public string $created_at;
  public string $updated_at;

  private function __construct(
    public bool $isadmin,
    public int $profile_picture,
    public string $email,
    public string $password,
    
  ) {
    $this->id = null;
    $this->created_at = null;
    $this->updated_at = null;
  }

  public static function findOneByEmail(string $email): User|null
  {
    $databaseConnection = new PDO(
      "mysql:host=mariadb;dbname=database",
      "user",
      "password"
    );

    $getUserQuery = $databaseConnection->prepare("SELECT id, is_email, profile_picture, email, password , created_at, updated_at FROM users WHERE email = :email");

    $getUserQuery->execute([
      "email" => $email
    ]);

    $user = $getUserQuery->fetch(PDO::FETCH_ASSOC);

    return new User($user["id"], $user["id_email"], $user["profile_picture"], $user["email"], $user["password"], $user["created_at"], $user["updated_at"]);
  }

  public function createUser()
  {
    $databaseConnection = new PDO(
      "mysql:host=mariadb;dbname=database",
      "user",
      "password"
    );

    $getUserQuery = $databaseConnection->prepare("INSERT INTO users (profile_picture, email, password, isadmin, created_at) VALUES :profile_picture, :email, :password, :isadmin, :created_at");

    $getUserQuery->execute([
      "email" => $this->email,
      "isadmin" => $this->isadmin,
      "profile_picture" => $this->profile_picture,
      "password" => $this->password,
      "created_at" => $this->created_at
    ]);
  }
  public function isValidPassword(string $password): bool
  {
    return password_verify($password, $this->password);
  }

}
