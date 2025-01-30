<?php

namespace Core;

use PDO;

class MigrationManager
{
    private PDO $pdo;
    private string $migrationsTable = 'migrations';

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->createMigrationsTable();
    }

    private function createMigrationsTable(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS {$this->migrationsTable} (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    private function getExecutedMigrations(): array
    {
        $stmt = $this->pdo->query("SELECT migration FROM {$this->migrationsTable}");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function migrate(string $migrationsPath): void
    {
        $executedMigrations = $this->getExecutedMigrations();


        $files = scandir($migrationsPath);

        $newMigrations = [];
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php' && !in_array($file, $executedMigrations)) {
                require_once "$migrationsPath/$file";
                $className = $this->getMigrationClassName("$migrationsPath/$file");
                if ($className && class_exists($className)) {
                    echo "Migrating: {$className}\n";
                    $migration = new $className();
                    $migration->up();
                    echo "Migrated: {$className}\n";

                    $newMigrations[] = $file;
                } else {
                    echo "Skipping: {$file} (Class not found)\n";
                }
            }
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            echo "No new migrations to apply.\n";
        }
    }

    public function rollback(string $migrationsPath): void
    {
        $executedMigrations = $this->getExecutedMigrations();

        if (empty($executedMigrations)) {
            echo "No migrations to rollback.\n";
            return;
        }

        $lastMigration = end($executedMigrations);
        require_once "$migrationsPath/$lastMigration";

        $className = $this->getMigrationClassName("$migrationsPath/$lastMigration");

        if ($className && class_exists($className)) {
            echo "Rolling back: {$className}\n";
            $migration = new $className();
            $migration->down();
            echo "Rolled back: {$className}\n";

            $this->removeMigration($lastMigration);
        } else {
            echo "Skipping rollback: {$lastMigration} (Class not found)\n";
        }
    }

    
    /**
     * Affiche la liste des migrations réalisés
     */
    public function listMigrations(): void
    {
        $migrations = $this->getExecutedMigrations();
        if (empty($migrations)) {
            echo "No migrations applied.\n";
        } else {
            echo "Executed migrations:\n";
            foreach ($migrations as $migration) {
                echo " - $migration\n";
            }
        }
    }

    /**
     * Save The migration in the Database Table
     */
    private function saveMigrations(array $migrations): void
    {
        foreach ($migrations as $migration) {
            $stmt = $this->pdo->prepare("INSERT INTO {$this->migrationsTable} (migration) VALUES (:migration)");
            $stmt->execute(['migration' => $migration]);
        }
    }

    

    /**
     * Supprime une migration de la table `migrations`
     */
    private function removeMigration(string $migration): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->migrationsTable} WHERE migration = :migration");
        $stmt->execute(['migration' => $migration]);
    }


    

    /**
     * Retourne le nom de la class d'un fichier de migration
     */
    private function getMigrationClassName(string $filePath): ?string
    {
        $contents = file_get_contents($filePath);
        if (preg_match('/namespace\s+([^;]+);/i', $contents, $namespaceMatch) &&
            preg_match('/class\s+([a-zA-Z0-9_]+)/i', $contents, $classMatch)) {
            return trim($namespaceMatch[1]) . '\\' . trim($classMatch[1]);
        }

        return null;
    }
}