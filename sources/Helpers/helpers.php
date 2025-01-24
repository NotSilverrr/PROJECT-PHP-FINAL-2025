<?php
if (!function_exists('view')) {
    function view(string $viewPath, array $data = []): void
    {
        $viewFile = __DIR__ . '/../views/' . str_replace('.', '/', $viewPath) . '.php';

        if (!file_exists($viewFile)) {
            throw new Exception("La vue [$viewPath] n'existe pas : $viewFile");
        }

        extract($data);

        include $viewFile;
    }
}