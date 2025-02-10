<?php

require_once __DIR__ . '/Core/Database.php';
require_once __DIR__ . '/Core/MigrationManager.php';
require_once __DIR__ . '/Core/SeederManager.php';

use Core\MigrationManager;
use Core\SeederManager;

$command = $argv[1] ?? null;
$force = isset($argv[2]) && $argv[2] === '--force';

$migrationManager = new MigrationManager();
$seederManager = new SeederManager(__DIR__ . '/Database/Seeders');

switch ($command) {
    case 'migrate':
        $migrationManager->migrate(__DIR__ . '/Database/Migrations');
        break;

    case 'rollback':
        $migrationManager->rollback(__DIR__ . '/Database/Migrations');
        break;

    case 'status':
        $migrationManager->listMigrations();
        break;

    case 'seed':
        $seederManager->runSeeders($force);
        break;

    default:
        echo "Usage:\n";
        echo "  php migrate.php migrate       # Exécute les migrations\n";
        echo "  php migrate.php rollback      # Annule la dernière migration\n";
        echo "  php migrate.php status        # Affiche les migrations exécutées\n";
        echo "  php migrate.php seed          # Exécute les seeders non appliqués\n";
        echo "  php migrate.php seed --force  # Exécute tous les seeders (même ceux déjà appliqués)\n";
        break;
}
