<?php

require_once __DIR__ . '/Core/Database.php';
require_once __DIR__ . '/Core/MigrationManager.php';

use Core\MigrationManager;

$command = $argv[1] ?? null;
$migrationManager = new MigrationManager();

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

    default:
        echo "Usage:\n";
        echo "  php migrate.php migrate   # Exécute les migrations\n";
        echo "  php migrate.php rollback  # Annule la dernière migration\n";
        echo "  php migrate.php status    # Affiche les migrations exécutées\n";
        break;
}
