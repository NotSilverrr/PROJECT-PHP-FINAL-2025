<?php

class User
{
  private function __construct(
    public int $id,
    public int $isadmin,
    public int $profile_picture,
    public string $email,
    public string $password,
    public string $created_at,
    public string $updated_at
  ) {}

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

  public function isValidPassword(string $password): bool
  {
    return password_verify($password, $this->password);
  }
}
