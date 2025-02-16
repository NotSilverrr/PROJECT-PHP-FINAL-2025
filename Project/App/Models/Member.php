<?php 
namespace App\Models;

use App\Services\Auth;
use Core\QueryBuilder;

class Member {
  public function __construct(
    public ?int $id = null,
    public int $userId,
    public int $groupId,
    public ?bool $read_only = false,
  ) {}

  private function isAdmin(): bool
  {
    $user = Auth::user();
    return $user && $user->isadmin == '1';
  }

  public function addMember()
  {
    if($this->isAdmin() || Group::isOwner($this->groupId)) {
      if(Group::isMember($this->groupId, $this->userId)) {
        throw new \Exception("Cet utilisateur est deja membre de ce groupe");
      }
      $query = new QueryBuilder;
      $query->insert()->into("user_group", ["group_id", "user_id"])->values([$this->groupId, $this->userId])->execute();
    } else {
      throw new \Exception("Vous n'êtes pas le propriétaire de ce groupe");
    }
  }

  public function deleteMember()
  {
    if($this->isAdmin() || Group::isOwner($this->groupId)) {
      $query = new QueryBuilder;
      $query->delete()->from("user_group")->where("group_id", "=", $this->groupId)->andWhere("user_id", "=", $this->userId)->execute();
    } else {
      throw new \Exception("Vous n'êtes pas l'owner de ce groupe");
    }
  }

  public function canEdit($groupId, $userId)
  {
    if($this->isAdmin()) {
      return true;
    }
    
    $query = new QueryBuilder;
    $response = $query->select(["read_only"])->from("user_group")->where("group_id", "=", $groupId)->andWhere("user_id", "=", $userId)->fetch();
    if($response["read_only"]) {
      return false;
    }
    return true;
  }
}