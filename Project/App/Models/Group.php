<?php
namespace App\Models;

use App\Services\Auth;
use Core\Database;
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
        $response = $query->select()->from("groups")->where("groups.id", "=", $id)->fetch();

        $group = new Group($response["id"], $response["name"], $response["profile_picture"], $response["owner"], $response["created_at"], $response["updated_at"]);
        
        return $group;

    }

    public static function getGroupsByUser(int $userId)
    {
        $query = new QueryBuilder;
        $response = $query->select(["id","name", "profile_picture", "owner"])->from("groups")->join("user_group", "groups.id", "=", "user_group.group_id")->where("user_group.user_id","=", $userId)->fetchAll();
        // transforme la réponse en objet group
        $groups = [];
        foreach ($response as $group) {
            $groups[] = new Group($group["id"], $group["name"], $group["profile_picture"], $group["owner"], new DateTime(), new DateTime());
        }

        return $groups;
    }

    public static function getMembers(int $groupId, string $search = "")
    {
        $search = "%$search%";
        $query = new QueryBuilder;
        $response = $query->select(["users.id", "users.first_name", "users.last_name", "users.profile_picture"])->from("users")->join("user_group", "users.id", "=", "user_group.user_id")->where("user_group.group_id", "=", $groupId)->andWhere("users.first_name", "LIKE", $search)->fetchAll();
        $members = [];
        foreach ($response as $member) {
            $members[] = new User($member["id"], $member["first_name"], $member["last_name"], $member["profile_picture"], "", "", "");
        }
        return $members;
    }

    public function createGroup()
    {
      $queryBuilder = new QueryBuilder();
      
      $data = [
        "name" => $this->name,
        "profile_picture" => $this->profile_picture,
        "owner" => $this->ownerId
      ];

      $columns = array_keys($data);
      
      $statement = $queryBuilder->insert()
        ->into('groups', $columns)
        ->values($data)
        ->execute();

      $this->id = $queryBuilder->lastInsertId();
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
