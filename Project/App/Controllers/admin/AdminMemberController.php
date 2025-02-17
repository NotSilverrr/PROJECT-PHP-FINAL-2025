<?php

namespace App\Controllers\admin;

use App\Models\Member;
use App\Requests\MemberRequest;
use App\Services\Auth;

class AdminMemberController
{
    private static function checkAdminAuth()
    {
        if (!Auth::check() || !Auth::isadmin()) {
            header('Location: /login');
            exit;
        }
    }

    public function add(int $id)
    {
        self::checkAdminAuth();
        
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
        self::checkAdminAuth();
        
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