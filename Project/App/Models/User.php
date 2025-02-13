<?php

namespace App\Models;

use Core\QueryBuilder;
use PDO;

class User
{
  public function __construct(
    public ?int $id = null,
    public string $first_name,
    public string $last_name,
    public ?string $profile_picture,
    public bool $isadmin,
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
      $user["first_name"],
      $user["last_name"],
      $user["profile_picture"] ?? null,
      (bool)$user["is_admin"],
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
      $user["first_name"],
      $user["last_name"],
      $user["profile_picture"] ?? null,
      (bool)$user["is_admin"],
      $user["email"],
      $user["password"],
      $user["created_at"],
      $user["updated_at"]
  );
  }

  public function createUser()
  {
    $queryBuilder = new QueryBuilder();
    
    $data = [
      "email" => $this->email,
      "first_name" => $this->first_name,
      "last_name" => $this->last_name,
      "is_admin" => (int)$this->isadmin,
      "profile_picture" => $this->profile_picture,
      "password" => password_hash($this->password, PASSWORD_DEFAULT),
      "created_at" => $this->created_at
    ];

    $columns = array_keys($data);
    
    $statement = $queryBuilder->insert()
      ->into('users', $columns)
      ->values($data)
      ->execute();

    $this->id = $queryBuilder->lastInsertId();
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
      "first_name" => $this->first_name,
      "last_name" => $this->last_name,
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
