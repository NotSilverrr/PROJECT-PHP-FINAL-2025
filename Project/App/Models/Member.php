<?php 
namespace App\Models;

use App\Services\Auth;
use Core\QueryBuilder;
use DateTime;

class Member {
  public function __construct(
    public ?int $id = null,
    public int $userId,
    public ?int $groupId = null,
    public ?DateTime $created_at = null,
    public ?bool $read_only = false,
    public ?User $user = null
  ) {}


  public function addMember()
  {
    $group = Group::getOneById($this->groupId);
    if(Auth::isadmin() || $group->isOwner(Auth::id())) {
      if($group->isMember($this->userId)) {
        throw new \Exception("Cet utilisateur est deja membre de ce groupe");
      }
      $readOnlyValue = $this->read_only === '' ? NULL : (int)$this->read_only;
      $query = new QueryBuilder;
      $query->insert()->into("user_group", ["group_id", "user_id", "read_only"])->values([$this->groupId, $this->userId, $readOnlyValue])->execute();
    } else {
      throw new \Exception("Vous n'êtes pas le propriétaire de ce groupe");
    }
  }

  public function deleteMember()
  {
    $group = Group::getOneById($this->groupId);
    if(Auth::isadmin() || $group->isOwner(Auth::id())) {
      $query = new QueryBuilder;
      $query->delete()->from("user_group")->where("group_id", "=", $this->groupId)->andWhere("user_id", "=", $this->userId)->execute();
    } else {
      throw new \Exception("Vous n'êtes pas l'owner de ce groupe");
    }
  }

  public static function canEdit($groupId, $userId)
  {    
    $query = new QueryBuilder;
    $response = $query->select(["read_only"])->from("user_group")->where("group_id", "=", $groupId)->andWhere("user_id", "=", $userId)->fetch();
    if($response["read_only"]) {
      return false;
    }
    return true;
  }

  public static function findOne(int $groupId, int $userId): Member|null
{
    $query = new QueryBuilder;
    $response = $query->select(["user_group.*", "users.first_name","users.last_name", "users.id"])  // Sélectionner toutes les colonnes de user_group et users
                      ->from("user_group")
                      ->join("users", "users.id", "=", "user_group.user_id")  // Jointure sur la table users
                      ->where("user_group.group_id", "=", $groupId)
                      ->andWhere("user_group.user_id", "=", $userId)
                      ->fetch();
    
    if(!$response) {
        return null;
    }
    
    return new Member(
        id: $response["id"],
        userId: $response["user_id"],
        groupId: $response["group_id"],
        read_only: $response["read_only"],
        created_at: new DateTime($response["created_at"]),
        user: new User(  
            id: $response["user_id"],
            first_name: $response["first_name"],
            last_name: $response["last_name"],
        )
    );
  }

  public function updateMember() {
    $readOnlyValue = $this->read_only === '' ? NULL : (int)$this->read_only;
    $query = new QueryBuilder;
    return $query->update()->from("user_group")->set(["read_only" => $readOnlyValue])->where("group_id", "=", $this->groupId)->andWhere("user_id", "=", $this->userId)->executeUpdate();
  }

}