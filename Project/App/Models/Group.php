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
    public ?string $profile_picture = null,
    public ?int $ownerId = null,
    public ?User $owner = null,
    public ?string $created_at = null,
    public ?string $updated_at = null
  ) {}

    public function isMember(int $userId)
    {
        $query = new QueryBuilder;
        $response = $query->select()->from("user_group")->where("user_id", "=", $userId)->andWhere("group_id", "=", $this->id)->fetch();
        if($response) {
            return true;
        }

        return false;
    }

    public function isOwner(int $userId)
    {
        $query = new QueryBuilder;
        $response = $query->select(["owner"])->from("groups")->where("id", "=", $this->id)->fetch();
        if($response["owner"] == $userId) {
            return true;
        }
        return false;
    }

    public static function exist(int $groupId)
    {
      $query = new QueryBuilder;
      $response = $query->select()->from("groups")->where("id", "=", $groupId)->fetch();
      if($response) {
        return true;
      }

      return false;
    }

    public static function getOneById(int $id)
    {
        $query = new QueryBuilder;
        $response = $query->select()->from("groups")->where("groups.id", "=", $id)->fetch();

        if (!$response) {
            return null;
        }

        $group = new Group(
          id: $response["id"],
          name: $response["name"], 
          profile_picture: $response["profile_picture"], 
          ownerId: $response["owner"], 
          created_at: $response["created_at"], 
          updated_at :$response["updated_at"]);
        
        return $group;

    }

    public static function getAllGroup(string $search = "")
    {
      $search = "%$search%";
      $queryBuilder = new QueryBuilder();
      $response = $queryBuilder->select(["groups.id as group_id","groups.profile_picture as group_profile_picture","groups.*","users.id as user_id","users.profile_picture as user_profile_picture","users.*"])->from("groups")->join("users","groups.owner","=", "users.id")->where("name", "LIKE", $search)->fetchAll();
      $groups = [];
      foreach ($response as $group) {
          $groups[] = new Group(
            id: $group["group_id"],
            name: $group["name"], 
            profile_picture: $group["group_profile_picture"], 
            owner: new User(id:$group["user_id"], first_name:$group["first_name"], last_name:$group["last_name"], profile_picture:$group["user_profile_picture"], isadmin:$group["is_admin"], email:$group["email"]),
            created_at: $group["created_at"], 
            updated_at :$group["updated_at"]);
      }
      return $groups;
    }

    public static function getGroupsByUser(int $userId)
    {
        $query = new QueryBuilder;
        $response = $query->select(["id","name", "profile_picture", "owner"])->from("groups")->join("user_group", "groups.id", "=", "user_group.group_id")->where("user_group.user_id","=", $userId)->orderBy('groups.created_at', 'ASC')->fetchAll();
        // transforme la réponse en objet group
        $groups = [];
        foreach ($response as $group) {
            $groups[] = new Group($group["id"], $group["name"], $group["profile_picture"], $group["owner"]);
        }

        return $groups;
    }

    public static function getMembers(int $groupId, string $search = "")
    {
        $search = "%$search%";
        $query = new QueryBuilder;
        $response = $query->select(["users.id", "users.first_name", "users.last_name", "users.profile_picture","users.is_admin","users.email"])->from("users")->join("user_group", "users.id", "=", "user_group.user_id")->where("user_group.group_id", "=", $groupId)->andWhere("users.first_name", "LIKE", $search)->orderBy('user_group.created_at', 'ASC')->fetchAll();
        $members = [];
        foreach ($response as $member) {
            $members[] = new User($member["id"], $member["first_name"], $member["last_name"], $member["profile_picture"], $member["is_admin"], $member["email"], "");
        }
        return $members;
    }

    public function createGroup() {
      $queryBuilder = new QueryBuilder();
  
      $data = [
          "name" => $this->name,
          "profile_picture" => null, // Ajouté mais temporairement NULL
          "owner" => $this->ownerId
      ];
  
      $columns = array_keys($data);
  
      $queryBuilder->insert()
        ->into('groups', $columns)
        ->values($data)
        ->execute();

      $this->id = $queryBuilder->lastInsertId();
      $member = new Member(userId: $this->ownerId, read_only: false);
      $this->addMember($member);
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

    public function addMember(Member $member)
    {
      if($this->isOwner(Auth::id())) {
        $readOnlyValue = $member->read_only === '' ? NULL : (int)$member->read_only;
        $query = new QueryBuilder;
        $query->insert()->into("user_group", ["group_id", "user_id", "read_only"])->values([$this->id, $member->userId, $readOnlyValue])->execute();
      }
    }

    public static function deleteMember(int $groupId, int $userId)
    {
      if(self::isOwner($groupId)) {
        $query = new QueryBuilder;
        $query->delete()->from("user_group")->where("group_id", "=", $groupId)->andWhere("user_id", "=", $userId)->execute();
      }
        
    }

    public function deleteAllMembers()
    {
      $query = new QueryBuilder;
      $query->delete()->from("user_group")->where("group_id", "=", $this->id)->execute();
    }

    public function delete()
    {
      $this->deleteAllMembers();
      $query = new QueryBuilder;
      $query->delete()->from("groups")->where("id", "=", $this->id)->execute();
      
    }

    

}
