<?php
namespace App\Models;

use Core\QueryBuilder;
use PDO;

class Group {


    public function __construct(
        public ?int $id = null,
        public string $name,
        public ?string $profile_picture,
        public int $ownerId,
        public ?string $created_at = null,
        public ?string $updated_at = null
      ) {}
    public static function getOneById(int $id)
    {
        $query = new QueryBuilder;
        $response = $query->select()->from("groups")->where("id", "=", $id)->fetch();
        
        return $response;

    }

    public static function getGroupsByUser()
    {
        $query = new QueryBuilder;
        $response = $query->select(["name", "profile_picture", "owner"])->from("groups")->join("user_group", "groups.id", "=", "user_group.group_id")->where("user_group.user_id","=", "1")->fetchAll();
        return $response;
    }

    public function createGroup()
    {
      $databaseConnection = new PDO(
        "mysql:host=mariadb;dbname=database",
        "user",
        "password"
      );
  
      $addGroupQuery = $databaseConnection->prepare("INSERT INTO groups (name, profile_picture, owner) VALUES (:name, :profile_picture, :owner)");
  
      $addGroupQuery->execute([
        "name" => $this->name,
        "profile_picture" => $this->profile_picture,
        "owner" => $this->ownerId,
      ]);
  
      $this->id = (int) $databaseConnection->lastInsertId();
    }

    public function update(): bool
    {
      $queryBuilder = new QueryBuilder();
      $data = [
        "name" => $this->name,
        "profile_picture" => $this->profile_picture,
        "owner" => $this->ownerId,
        'updated_at' => date('Y-m-d H:i:s')
      ];
  
      return $queryBuilder->update()->from('groups')->set($data)->where('id', '=', $this->id)->executeUpdate();
    }

}
