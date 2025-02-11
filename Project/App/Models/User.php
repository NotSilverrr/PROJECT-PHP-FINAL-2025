<?php

namespace App\Models;

use Core\QueryBuilder;
use PDO;

class User
{
  public function __construct(
    public ?int $id = null,
    public bool $isadmin,
    public ?string $profile_picture,
    public string $email,
    public string $password,
    public ?string $created_at = null,
    public ?string $updated_at = null
  ) {}

  public static function findOneByEmail(string $email): User|null
  {


    $query = new QueryBuilder;
    $user = $query->select()->from("users")->where("email", "=", $email)->fetch();
    
    if (!$user) {
        return null;
    }
    
    return new User(
      $user["id"],
      (bool)$user["is_admin"],
      $user["profile_picture"] ?? null,
      $user["email"],
      $user["password"],
      $user["created_at"],
      $user["updated_at"]
  );
  }

  public static function findOneById(int $id)
  {
    $query = new QueryBuilder;
    $user = $query->select()->from("users")->where("id", "=", $id)->fetch();
    return new User(
      $user["id"],
      (bool)$user["is_admin"],
      $user["profile_picture"] ?? null,
      $user["email"],
      $user["password"],
      $user["created_at"],
      $user["updated_at"]
  );
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

    $this->id = (int) $databaseConnection->lastInsertId();
  }
  public function isValidPassword(string $password): bool
  {
    return password_verify($password, $this->password);
  }

  public function update(): bool
  {
    $queryBuilder = new QueryBuilder();
    $data = [
      'email' => $this->email,
      'is_admin' => (int)$this->isadmin,
      'profile_picture' => $this->profile_picture,
      'updated_at' => date('Y-m-d H:i:s')
    ];

    if ($this->password && !password_get_info($this->password)['algo']) {
      $data['password'] = password_hash($this->password, PASSWORD_DEFAULT);
    }

    return $queryBuilder->update()->from('users')->set($data)->where('id', '=', $this->id)->executeUpdate();
  }

  public function delete(): bool
  {
    $queryBuilder = new QueryBuilder();
    return $queryBuilder->delete()->from('users')->where('id', '=', $this->id)->execute();
  }
}
