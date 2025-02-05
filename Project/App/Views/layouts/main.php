<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Mon Application' ?></title>
    <link rel="stylesheet" href="/dist/framework-esgi.css">
    <?= $styles ?? '' ?>
</head>
<body>
    <header>
        Coucou c'est le header
    </header>

    <main>
        <?= $content ?>
    </main>

    <footer>
        <!-- Votre pied de page -->
    </footer>

    <script src="/dist/framework-esgi.js"></script>
    <?= $scripts ?? '' ?>
</body>
</html>