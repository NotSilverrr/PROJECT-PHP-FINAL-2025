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
    $group = Group::getOneById($groupId);
    if (!($group->isOwner(Auth::id()) || Auth::isadmin())) {
      return view("errors.403");
    }
    $members = Group::getMembers($groupId, $_GET['m'] ?? "");
    $user = Member::findOne($groupId, $userId);
    return view("group.member", ["user" => $user, "members" => $members, "group" => $group]);
  }

  public function create (int $id)
  {

    $allUsers = User::getAllUsers($_GET['u'] ?? "");
    $members = Group::getMembers($id, $_GET['m'] ?? "");
    $allMembers = Group::getMembers($id);
    $group = Group::getOneById($id);

    $memberIds = array_column($allMembers, 'id');
    $available_users = array_filter($allUsers, function($user) use ($memberIds) {
        return !in_array($user->id, $memberIds);
    });
    return view("group.addMember", ["allUsers" => $available_users, "groupId" => $id, "members" => $members, "group" => $group]);
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

        $group = Group::getOneById($id);
        if ($group->isMember($request->userId)) {
          throw new \Exception("L'utilisateur est déjà membre du groupe");
        }
        $group->addMember($member);

        $_SESSION['success'] = "Membre ajouté avec succès";
        header("Location:/group/".$id);
        exit;
    } catch (\Exception $e) {
      $_SESSION['error'] = $e->getMessage();
      header("Location:/group/".$id."/addMember");
      exit;
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
        $_SESSION['success'] = "Membre supprimé avec succès";
        header("Location:/group/".$id);
        exit;
    } catch (\Exception $e) {
      $_SESSION['error'] = $e->getMessage();
      header("Location:/group/".$id);
      exit;
    }
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

        if($member->updateMember()) {
          $_SESSION['success'] = "Membre modifié avec succès";
          header("Location:/group/".$groupId);
          exit;
        } else {
          throw new \Exception("Erreur lors de la mise à jour du membre");
        }
    } catch (\Exception $e) {
      $_SESSION['error'] = $e->getMessage();
      header("Location:/group/".$groupId);
      exit;
    }
  }
}