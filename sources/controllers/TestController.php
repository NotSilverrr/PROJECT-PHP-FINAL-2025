<?php

namespace App\Controllers;

class TestController {
    public static function test() {
        // require __DIR__ . '/../views/layouts/main.php';
        return view('test.index', ['article' => "C'est mon article coucou", "array" => ['hello', 'bonjour', 'hola', 'quoicoubeh']]);
    }
}