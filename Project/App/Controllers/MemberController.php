<?php

namespace App\Controllers;

use App\Models\Group;
use App\Models\Member;
use App\Models\User;
use App\Requests\MemberRequest;

class MemberController {

  public function create (int $id)
  {

    $allUsers = User::getAllUsers($_GET['u'] ?? "");
    $members = Group::getMembers($id, $_GET['m'] ?? "");
    $group = Group::getOneById($id);
    return view("group.addMember", ["allUsers" => $allUsers, "groupId" => $id, "members" => $members, "group" => $group]);
  }
  public function store(int $id)
  {
    $request = new MemberRequest;
    try {
        $member = new Member(
          userId: $request->userId,
          groupId: $id
        );

        $member->addMember();
        header("Location:/group/".$id);
        exit;
    } catch (\Exception $e) {
      return view("group.addMember", [
        "error" => $e->getMessage(),
        "groupId" => $id,
        "allUsers" => User::getAllUsers(),
        "members" => Group::getMembers($id),
        "group" => Group::getOneById($id)
    ]);
    }
  }

  public function delete(int $id)
  {
    $request = new MemberRequest;
    try {
        $member = new Member(
          userId: $request->userId,
          groupId: $id
        );

        $member->deleteMember();
        header("Location:/group/".$id);
    } catch (\Exception $e) {
      echo $e->getMessage();
    }
  }
}