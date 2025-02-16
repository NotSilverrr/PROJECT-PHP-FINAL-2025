<?php

namespace App\Controllers;

use App\Models\Group;
use App\Models\Member;
use App\Models\User;
use App\Requests\MemberRequest;
use App\Services\Auth;

class MemberController {

  public function show(int $groupId, int $userId)
  {
    if (!Group::isOwner($groupId, Auth::id())) {
      return view("errors.403");
    }
    $member = Member::findOne($groupId, $userId);
    return view("group.member", ["member" => $member]);
  }

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
          read_only: $request->readOnly,
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

  public function edit(int $groupId, int $userId)
  {
    $member = Member::findOne($groupId, $userId);
    return view("group.editMember", ["member" => $member]);
  }

  public function update(int $groupId, int $userId)
  {
    $request = new MemberRequest;

    try {
        $member = new Member(
          userId: $request->userId,
          read_only: $request->readOnly,
          groupId: $groupId
        );

        $member->updateMember();
        header("Location:/group/".$groupId);
        exit;
    } catch (\Exception $e) {
      return view("group.member", [
        "error" => $e->getMessage(),
        "groupId" => $groupId,
        "userId" => $userId,
        "member" => Member::findOne($groupId, $userId)
    ]);
    }
  }
}