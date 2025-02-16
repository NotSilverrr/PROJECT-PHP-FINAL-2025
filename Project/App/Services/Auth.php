<?php
namespace App\Services;

use App\Models\User;

class Auth
{
    public static function user()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']) ? User::findOneById($_SESSION['user_id']) : null;
    }

    public static function id()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['user_id'] ?? null;
    }

    public static function isadmin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['isadmin'] ?? null;
    }

    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['login']) && $_SESSION['login'] === 1;
    }
}