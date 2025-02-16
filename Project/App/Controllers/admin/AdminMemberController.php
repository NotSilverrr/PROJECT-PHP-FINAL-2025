<?php

namespace App\Controllers\admin;

use App\Core\Controller;
use App\Models\User;
use App\Models\Group;
use App\Models\Member;
use App\Requests\MemberRequest;

class AdminMemberController
{
    public function add(int $id)
    {
      session_start();
      $request = new MemberRequest;
      try {
          $member = new Member(
            userId: $request->userId,
            groupId: $id
          );
  
          $member->addMember();
          header("Location:/admin/group/update/".$id);
          exit;
      } catch (\Exception $e) {
          $_SESSION['error'] = $e->getMessage();
          header("Location:/admin/group/update/".$id);
          exit;
      }
    }
  
    public function delete(int $id)
    {
      session_start();
      $request = new MemberRequest;
      try {
          $member = new Member(
            userId: $request->userId,
            groupId: $id
          );
  
          $member->deleteMember();
          header("Location:/admin/group/update/".$id);
          exit;
      } catch (\Exception $e) {
          $_SESSION['error'] = $e->getMessage();
          header("Location:/admin/group/update/".$id);
          exit;
      }
    }
  }