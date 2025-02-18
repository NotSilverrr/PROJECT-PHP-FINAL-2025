<?php
namespace App\Services;

use App\Models\User;

class Auth
{
    public static function user()
    {
        startSession();
        return isset($_SESSION['user_id']) ? User::findOneById($_SESSION['user_id']) : null;
    }

    public static function id()
    {
        startSession();
        return $_SESSION['user_id'] ?? null;
    }

    public static function isadmin()
    {
        startSession();
        return $_SESSION['isadmin'] ?? null;
    }

    public static function check()
    {
        startSession();
        return isset($_SESSION['login']) && $_SESSION['login'] === 1;
    }
}