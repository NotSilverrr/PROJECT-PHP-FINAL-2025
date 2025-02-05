<?php

namespace App\Controllers\Admin;

class AdminController {
    public static function test() {
        // require __DIR__ . '/../views/layouts/main.php';
        session_start();
        if (isset($_SESSION['login'])){
            echo 'Vous etes connecté';
        }else{
            echo 'Vous n\'etes pas connecté';
        }
        return view('test.index', ['article' => "C'est mon article coucou", "array" => ['hello', 'bonjour', 'hola', 'quoicoubeh']]);
    }
}