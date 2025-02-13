<?php
namespace App\Models;

use App\Services\Auth;
use Core\Database;
use Core\QueryBuilder;
use DateTime;

class Group {

    public int $id;
    public string $name;
    public string $profile_picture;
    public int $ownerId;
    public DateTime $created_at;
    public DateTime $updated_at;


    public function __construct(int $id, string $name, string $profile_picture, int $ownerId, DateTime $created_at, DateTime $updated_at)
    {
        $this->id = $id;
        $this->name = $name;
        $this->profile_picture = $profile_picture;
        $this->ownerId = $ownerId;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public static function isMember(int $groupId)
    {
        $userId = Auth::id();
        $query = new QueryBuilder;
        $response = $query->select()->from("user_group")->where("user_id", "=", $userId)->andWhere("group_id", "=", $groupId)->fetch();
        return $response;
    }

    public static function isOwner(int $groupId)
    {
        $userId = Auth::id();
        $query = new QueryBuilder;
        $response = $query->select()->from("groups")->where("owner", "=", $userId)->andWhere("id", "=", $groupId)->fetch();
        return $response;
    }

    public static function getOneById(int $id)
    {
        $query = new QueryBuilder;
        $response = $query->select()->from("groups")->where("groups.id", "=", $id)->fetch();

        $group = new Group($response["id"], $response["name"], $response["profile_picture"], $response["owner"], new DateTime($response["created_at"]), new DateTime($response["updated_at"]));
        
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

    public static function deleteMember(int $groupId, int $userId)
    {
        $query = new QueryBuilder;
        $query->delete()->from("user_group")->where("group_id", "=", $groupId)->andWhere("user_id", "=", $userId)->execute();
    }

}
