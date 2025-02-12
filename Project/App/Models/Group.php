<?php
namespace App\Models;

use Core\Database;
use Core\QueryBuilder;
use DateTime;

class Group {

    private int $id;
    private string $name;
    private string $profile_picture;
    private int $ownerId;
    private DateTime $created_at;
    private DateTime $updated_at;


    public function __construct()
    {
        
    }

    public static function getOneById(int $id)
    {
        $query = new QueryBuilder;
        $response = $query->select()->from("groups")->where("groups.id", "=", $id)->fetch();
        
        return $response;

    }

    public static function getGroupsByUser(int $userId)
    {
        $query = new QueryBuilder;
        $response = $query->select(["id","name", "profile_picture", "owner"])->from("groups")->join("user_group", "groups.id", "=", "user_group.group_id")->where("user_group.user_id","=", $userId)->fetchAll();
        return $response;
    }

    public static function getMembers(int $groupId)
    {
        $query = new QueryBuilder;
        $response = $query->select(["users.id", "users.first_name", "users.last_name", "users.profile_picture"])->from("users")->join("user_group", "users.id", "=", "user_group.user_id")->where("user_group.group_id", "=", $groupId)->fetchAll();
        return $response;
    }

}
