<?php

namespace App\Models;

use Core\QueryBuilder;
use PDO;

class Photo
{


  public function __construct(
    public ?int $id = null,
    public string $file,
    public int $group_id,
    public int $user_id,
    public ?string $created_at = null,
    public ?string $updated_at = null,
    public ?User $user = null
  ) {}


  public static function findOneById(int $id): Photo|null
  {
    $query = new QueryBuilder;
    $response = $query->select()->from("photos")->where("id", "=", $id)->fetch();
    return new Photo(
      id: $response["id"],
      file: $response["file"],
      group_id: $response["group_id"],
      user_id: $response["user_id"],
      created_at: $response["created_at"],
      updated_at: $response["updated_at"]
    );
}

  public static function findByGroupId(int $groupId): array
  {

    $query = new QueryBuilder;
    $photos = $query->select(["photos.*", "users.first_name", "users.last_name"])->from("photos")->join("users","photos.user_id", "=", "users.id")->where("group_id", "=", $groupId)->fetchAll();

    $photoObjects = [];
    
    foreach ($photos as $photo) {
      $user = new User(
          id: $photo["user_id"],
          first_name: $photo["first_name"],
          last_name: $photo["last_name"],
      );

      $photoObj = new Photo(
          null,
          $photo["file"],
          $photo["group_id"],
          $photo["user_id"]
      );
      $photoObj->id = $photo["id"];
      $photoObj->created_at = $photo["created_at"];
      $photoObj->updated_at = $photo["updated_at"];
      $photoObj->user = $user;
      
      $photoObjects[] = $photoObj;
  }
  // echo "<pre>";
  // print_r($photoObjects);
  
  return $photoObjects;
  }

  public function createPhoto()
  {
    $queryBuilder = new QueryBuilder();
    
    $data = [
      "file" => $this->file,
      "group_id" => $this->group_id,
      "user_id" => $this->user_id,
    ];

    $columns = array_keys($data);
    
    $statement = $queryBuilder->insert()
      ->into('photos', $columns)
      ->values($data)
      ->execute();

    $this->id = $queryBuilder->lastInsertId();
  }

  public function update(): bool
  {
    $queryBuilder = new QueryBuilder();
    $data = [
      "file" => $this->file,
      "group_id" => $this->group_id,
      "user_id" => $this->user_id,
      'updated_at' => date('Y-m-d H:i:s')
    ];

    return $queryBuilder->update()->from('photos')->set($data)->where('id', '=', $this->id)->executeUpdate();
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

  public static function isOwner(int $photoId, int $userId): bool
  {
    $query = new QueryBuilder;
    $response = $query->select()->from("photos")->where("id", "=", $photoId)->fetch();
    return (int)$response["user_id"] === $userId;
  }
}
