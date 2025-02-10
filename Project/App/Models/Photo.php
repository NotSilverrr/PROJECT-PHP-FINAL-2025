<?php

namespace App\Models;

use Core\QueryBuilder;
use PDO;

class Photo
{
  public int $id;
  public string $created_at;
  public string $updated_at;

  public function __construct(
    public string $file,
    public int $group_id,
    public int $user_id
  ) {
    $this->id = 0;
    $this->created_at = date('Y-m-d H:i:s');
    $this->updated_at = date('Y-m-d H:i:s');
  }

  public static function findOneById(int $id): Photo|null
  {
    $databaseConnection = new PDO(
      "mysql:host=mariadb;dbname=database",
      "user",
      "password"
    );

    $getPhotoQuery = $databaseConnection->prepare("SELECT id, file, group_id, user_id, created_at, updated_at FROM photos WHERE id = :id");

    $getPhotoQuery->execute([
      "id" => $id
    ]);

    $photo = $getPhotoQuery->fetch(PDO::FETCH_ASSOC);
    
    if (!$photo) {
        return null;
    }
    
    $photoObj = new Photo(
        $photo["file"],
        $photo["group_id"],
        $photo["user_id"]
    );
    $photoObj->id = $photo["id"];
    $photoObj->created_at = $photo["created_at"];
    $photoObj->updated_at = $photo["updated_at"];
    return $photoObj;
  }

  public static function findByGroupId(int $groupId): array
  {

    $query = new QueryBuilder;
    $photos = $query->select()->from("photos")->where("group_id", "=", $groupId)->fetchAll();

    $photoObjects = [];
    
    foreach ($photos as $photo) {
        $photoObj = new Photo(
            $photo["file"],
            $photo["group_id"],
            $photo["user_id"]
        );
        $photoObj->id = $photo["id"];
        $photoObj->created_at = $photo["created_at"];
        $photoObj->updated_at = $photo["updated_at"];
        $photoObjects[] = $photoObj;
    }
    
    return $photoObjects;
  }

  public function createPhoto()
  {
    $databaseConnection = new PDO(
      "mysql:host=mariadb;dbname=database",
      "user",
      "password"
    );

    $createPhotoQuery = $databaseConnection->prepare(
        "INSERT INTO photos (file, group_id, user_id, created_at, updated_at) 
         VALUES (:file, :group_id, :user_id, :created_at, :updated_at)"
    );

    $createPhotoQuery->execute([
      "file" => $this->file,
      "group_id" => $this->group_id,
      "user_id" => $this->user_id,
      "created_at" => $this->created_at,
      "updated_at" => $this->updated_at
    ]);

    $this->id = (int)$databaseConnection->lastInsertId();
  }

  public function updatePhoto()
  {
    $databaseConnection = new PDO(
      "mysql:host=mariadb;dbname=database",
      "user",
      "password"
    );

    $updatePhotoQuery = $databaseConnection->prepare(
        "UPDATE photos 
         SET file = :file,
             updated_at = :updated_at
         WHERE id = :id"
    );

    $this->updated_at = date('Y-m-d H:i:s');

    $updatePhotoQuery->execute([
      "id" => $this->id,
      "file" => $this->file,
      "updated_at" => $this->updated_at
    ]);
  }

  public function deletePhoto()
  {
    $databaseConnection = new PDO(
      "mysql:host=mariadb;dbname=database",
      "user",
      "password"
    );

    $deletePhotoQuery = $databaseConnection->prepare("DELETE FROM photos WHERE id = :id");
    $deletePhotoQuery->execute([
      "id" => $this->id
    ]);
  }
}
