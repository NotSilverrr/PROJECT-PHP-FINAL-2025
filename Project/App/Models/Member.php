<?php 
namespace App\Models;

use Core\QueryBuilder;

class Member {
  public function __construct(
    public ?int $id = null,
    public int $userId,
    public int $groupId
  ) {}

  public function addMember()
    {
      if(Group::isOwner($this->groupId)) {
        $query = new QueryBuilder;
        $query->insert()->into("user_group", ["group_id", "user_id"])->values([$this->groupId, $this->userId])->execute();
      }
    }

  public function deleteMember()
  {
    if(Group::isOwner($this->groupId)) {
      $query = new QueryBuilder;
      $query->delete()->from("user_group")->where("group_id", "=", $this->groupId)->andWhere("user_id", "=", $this->userId)->execute();
    }
      
  }
}