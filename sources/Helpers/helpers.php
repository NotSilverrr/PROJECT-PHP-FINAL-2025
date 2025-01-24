<?php
if (!function_exists('view')) {
    function view(string $viewPath, array $data = []): void
    {
        // Convertir le chemin de type "articles.index" en "views/articles/index.php"
        $viewFile = __DIR__ . '/../views/' . str_replace('.', '/', $viewPath) . '.php';

        // Vérifier si le fichier existe
        if (!file_exists($viewFile)) {
            throw new Exception("La vue [$viewPath] n'existe pas : $viewFile");
        }

        // Extraire les variables du tableau pour qu'elles soient accessibles dans la vue
        extract($data);

        // Inclure le fichier de la vue
        include $viewFile;
    }
}