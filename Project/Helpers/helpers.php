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

function deleteFolder($folderPath) {
    $folderPath = __DIR__ . "/../" . $folderPath;
    if (!is_dir($folderPath)) return false;

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($folderPath, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($files as $file) {
        $file->isDir() ? rmdir($file) : unlink($file);
    }

    return rmdir($folderPath);
}

function startSession() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}