<?php
use Core\View;

function view($view, $data = []) {
    return View::make($view, $data);
}

function redirect($path) {
    header("Location: $path");
    exit();
}

function fileName($file) {
    $response = end(explode("/", $file));
    return $response;
}