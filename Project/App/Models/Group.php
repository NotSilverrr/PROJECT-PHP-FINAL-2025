<?php

namespace App\Models;

use PDO;

class Group
{
  public int $id;
  public string $created_at;
  public string $updated_at;

  public function __construct(
    public string $name,
    public string $profile_picture,
    public int $owner
  ) {
    $this->id = 0;
    $this->created_at = date('Y-m-d H:i:s');
    $this->updated_at = date('Y-m-d H:i:s');
  }

  public static function findOneById(int $id): Group|null
  {
    $databaseConnection = new PDO(
      "mysql:host=mariadb;dbname=database",
      "user",
      "password"
    );

    $getGroupQuery = $databaseConnection->prepare("SELECT id, name, profile_picture, owner, created_at, updated_at FROM groups WHERE id = :id");

    $getGroupQuery->execute([
      "id" => $id
    ]);

    $group = $getGroupQuery->fetch(PDO::FETCH_ASSOC);
    
    if (!$group) {
        return null;
    }
    
    $groupObj = new Group(
        $group["name"],
        $group["profile_picture"],
        $group["owner"]
    );
    $groupObj->id = $group["id"];
    $groupObj->created_at = $group["created_at"];
    $groupObj->updated_at = $group["updated_at"];
    return $groupObj;
  }

  public function createGroup()
  {
    $databaseConnection = new PDO(
      "mysql:host=mariadb;dbname=database",
      "user",
      "password"
    );

    $createGroupQuery = $databaseConnection->prepare(
        "INSERT INTO groups (name, profile_picture, owner, created_at, updated_at) 
         VALUES (:name, :profile_picture, :owner, :created_at, :updated_at)"
    );

    $createGroupQuery->execute([
      "name" => $this->name,
      "profile_picture" => $this->profile_picture,
      "owner" => $this->owner,
      "created_at" => $this->created_at,
      "updated_at" => $this->updated_at
    ]);

    $this->id = (int)$databaseConnection->lastInsertId();
  }

  public function updateGroup()
  {
    $databaseConnection = new PDO(
      "mysql:host=mariadb;dbname=database",
      "user",
      "password"
    );

    $updateGroupQuery = $databaseConnection->prepare(
        "UPDATE groups 
         SET name = :name, 
             profile_picture = :profile_picture,
             updated_at = :updated_at
         WHERE id = :id"
    );

    $this->updated_at = date('Y-m-d H:i:s');

    $updateGroupQuery->execute([
      "id" => $this->id,
      "name" => $this->name,
      "profile_picture" => $this->profile_picture,
      "updated_at" => $this->updated_at
    ]);
  }

  public function deleteGroup()
  {
    $databaseConnection = new PDO(
      "mysql:host=mariadb;dbname=database",
      "user",
      "password"
    );

    $deleteGroupQuery = $databaseConnection->prepare("DELETE FROM groups WHERE id = :id");
    $deleteGroupQuery->execute([
      "id" => $this->id
    ]);
  }
}
