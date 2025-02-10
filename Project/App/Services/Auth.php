<?php
namespace App\Services;

use App\Models\User;

class Auth
{
    public static function user()
    {
        session_start();
        return isset($_SESSION['user_id']) ? User::findOneById($_SESSION['user_id']) : null;
    }

    public static function id()
    {
        session_start();
        return $_SESSION['user_id'] ?? null;
    }

    public static function check()
    {
        session_start();
        return isset($_SESSION['login']) && $_SESSION['login'] === 1;
    }
}