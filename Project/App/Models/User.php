<?php

namespace App\Models;

use Core\QueryBuilder;
use DateTime;
use PDO;

class User
{
  public function __construct(
    public ?int $id = null,
    public ?string $first_name=null,
    public ?string $last_name=null,
    public ?string $profile_picture = null,
    public ?bool $isadmin = null,
    public ?string $email = null,
    public ?string $password = null,
    public ?string $reset_token = null,
    public ?DateTime $reset_token_expiration = null,
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
      id: $user["id"],
      first_name: $user["first_name"],
      last_name: $user["last_name"],
      profile_picture: $user["profile_picture"] ?? null,
      isadmin: (bool)$user["is_admin"],
      email: $user["email"],
      password: $user["password"],
      created_at: $user["created_at"],
      updated_at: $user["updated_at"]
  );
  }

  public static function findOneById(int $id)
  {
    $query = new QueryBuilder;
    $user = $query->select()->from("users")->where("id", "=", $id)->fetch();
    if (!$user) {
      return null;
    }
    
    return new User(
      id: $user["id"],
      first_name: $user["first_name"],
      last_name: $user["last_name"],
      profile_picture: $user["profile_picture"] ?? null,
      isadmin: (bool)$user["is_admin"],
      email: $user["email"],
      password: $user["password"],
      created_at: $user["created_at"],
      updated_at: $user["updated_at"]
    );
  }

  public function isAdmin(): bool
  {
    return $this->isadmin;
  }

  public static function getAllUsers(string $search = "")
  {
    $search = "%$search%";
    $queryBuilder = new QueryBuilder();
    $response = $queryBuilder->select()->from("users")->where("first_name", "LIKE", $search)->orWhere("last_name", "LIKE", $search)->fetchAll();
    $users = [];
    foreach ($response as $user) {
        $users[] = new User(
          id: $user["id"],
          first_name: $user["first_name"],
          last_name: $user["last_name"],
          profile_picture: $user["profile_picture"] ?? null,
          isadmin: (bool)$user["is_admin"],
          email: $user["email"],
          password: $user["password"],
          created_at: $user["created_at"],
          updated_at: $user["updated_at"]
        );
    }

    return $users;
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

  public function saveResetToken(string $token, \DateTime $expiration) {
    $query = new QueryBuilder;
    $query->update()
        ->from("users")
        ->set([
            "reset_token" => $token,
            "reset_token_expiration" => $expiration->format('Y-m-d H:i:s')
        ])
        ->where("id", "=", $this->id)
        ->execute();
  }

  public static function getByResetToken(string $token) {
    $query = new QueryBuilder;
    $user = $query->select()
        ->from("users")
        ->where("reset_token", "=", $token)
        ->fetch();

    if ($user === false) {
      // Aucun utilisateur trouvé avec ce token, donc retourner null ou gérer l'erreur
      return null; // Ou lancer une exception si tu préfères
    }

    // Assurer que les champs existent et ne sont pas null
    return new User(
        id: $user["id"] ?? null,
        first_name: $user["first_name"] ?? '', // Utiliser une valeur par défaut si `first_name` est null
        last_name: $user["last_name"] ?? '',   // Idem pour `last_name`
        profile_picture: $user["profile_picture"] ?? null,
        isadmin: (bool)($user["is_admin"] ?? false),
        email: $user["email"] ?? '',
        password: $user["password"] ?? '',
        reset_token: $user["reset_token"] ?? null,
        reset_token_expiration: $user["reset_token_expiration"] ? new DateTime($user["reset_token_expiration"]) : null, // Assurer que la date est bien formatée
        created_at: $user["created_at"] ?? null,
        updated_at: $user["updated_at"] ?? null
    );
  }

  public function updatePassword(string $password) {
    $query = new QueryBuilder;
    $query->update()
        ->from("users")
        ->set(["password" => $password])
        ->where("id", "=", $this->id)
        ->execute();
  }
}
