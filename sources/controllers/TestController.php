<?php

namespace App\Controllers;

class TestController {
    public static function test() {
        return view('test.index', ['article' => "C'est mon article coucou"]);
    }
}