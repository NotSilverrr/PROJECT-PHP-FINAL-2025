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

    public static function getGroupsByUser()
    {
        $query = new QueryBuilder;
        $response = $query->select(["name", "profile_picture", "owner"])->from("groups")->join("user_group", "groups.id", "=", "user_group.group_id")->where("user_group.user_id","=", "1")->fetchAll();
        return $response;
    }

}
