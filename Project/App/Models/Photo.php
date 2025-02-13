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
    public ?string $updated_at = null
  ) {}


  public static function findOneById(int $id): Photo|null
  {
    $query = new QueryBuilder;
    $response = $query->select()->from("photos")->where("id", "=", $id)->fetch();
    return $response;
}

  public static function findByGroupId(int $groupId): array
  {

    $query = new QueryBuilder;
    $photos = $query->select()->from("photos")->where("group_id", "=", $groupId)->fetchAll();

    $photoObjects = [];
    
    foreach ($photos as $photo) {
        $photoObj = new Photo(
            null,
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
    $queryBuilder = new QueryBuilder();
    
    $data = [
      "file" => $this->file,
      "group_id" => $this->group_id,
      "user_id" => $this->user_id,
      "created_at" => $this->created_at,
      "updated_at" => $this->updated_at
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
}
