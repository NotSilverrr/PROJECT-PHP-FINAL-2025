<?php

namespace App\Models;

use Core\QueryBuilder;
use DateTime;
use DOTNET;
use PDO;

class Photo
{


  public function __construct(
    public ?int $id = null,
    public string $file,
    public ?int $group_id = null,
    public ?int $user_id = null,
    public ?Group $group = null,
    public ?User $user = null,
    public ?string $share_token = null,
    public ?DateTime $share_token_expiration = null,
    public ?string $created_at = null,
    public ?string $updated_at = null,
  ) {}


  public static function findOneById(int $id): Photo|null
  {
    $query = new QueryBuilder;
    $response = $query->select()->from("photos")->where("id", "=", $id)->fetch();
    if (!$response) {
      return null;
    }
    
    return new Photo(
      id: $response["id"],
      file: $response["file"],
      group_id: $response["group_id"],
      user_id: $response["user_id"],
      share_token: $response["share_token"],
      share_token_expiration: $response["share_token_expiration"] ? new DateTime($response["share_token_expiration"]) : "",
      created_at: $response["created_at"],
      updated_at: $response["updated_at"]
    );
}

public static function getAllPhoto()
{
  $queryBuilder = new QueryBuilder();
  $response = $queryBuilder->select(["photos.id as photo_id","photos.*","groups.*","users.*"])->from("photos")->join("groups","photos.group_id","=", "groups.id")->join("users","photos.user_id","=", "users.id")->fetchAll();
  $photos = [];
  foreach ($response as $photo) {
      $photos[] = new Photo(
        id: $photo["photo_id"],
        file: $photo["file"],
        group: new Group(id:$photo["group_id"],name:$photo["name"]),
        user: new User(id: $photo["user_id"],email: $photo["email"]),
        created_at: $photo["created_at"],
        updated_at: $photo["updated_at"]
      );
  }

  return $photos;
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

  public function saveShareToken(string $token, DateTime $expiration)
  {
    $query = new QueryBuilder;
    $query->update()
        ->from("photos")
        ->set([
            "share_token" => $token,
            "share_token_expiration" => $expiration->format('Y-m-d H:i:s')
        ])
        ->where("id", "=", $this->id)
        ->execute();
  }
}
