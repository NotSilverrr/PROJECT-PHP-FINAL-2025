<?php

namespace Core;

use PDO;

class SeederManager
{
    private PDO $pdo;
    private string $seedersTable = 'seeders';
    private string $seederPath;

    public function __construct(string $seederPath)
    {
        $this->pdo = Database::getConnection();
        $this->seederPath = $seederPath;
        $this->createSeedersTable();
    }

    private function createSeedersTable(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS {$this->seedersTable} (
                id INT AUTO_INCREMENT PRIMARY KEY,
                seeder VARCHAR(255) NOT NULL,
                executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    private function getExecutedSeeders(): array
    {
        $stmt = $this->pdo->query("SELECT seeder FROM {$this->seedersTable}");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private function saveSeeder(string $seeder): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->seedersTable} (seeder) VALUES (:seeder)");
        $stmt->execute(['seeder' => $seeder]);
    }

    public function runSeeders(bool $force = false): void
    {
        $executedSeeders = $force ? [] : $this->getExecutedSeeders();
        $files = scandir($this->seederPath);
        $newSeeders = [];

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php' && (!in_array($file, $executedSeeders) || $force)) {
                require_once "{$this->seederPath}/$file";

                $className = $this->getSeederClassName("{$this->seederPath}/$file");

                if ($className && class_exists($className)) {
                    echo "Seeding: {$className}\n";
                    $seeder = new $className();
                    $seeder->run();
                    echo "Seeded: {$className}\n";

                    $newSeeders[] = $file;
                } else {
                    echo "Skipping: {$file} (Class not found: $className)\n";
                }
            }
        }

        if (!$force) {
            foreach ($newSeeders as $seeder) {
                $this->saveSeeder($seeder);
            }
        }

        echo empty($newSeeders) ? "No new seeders to apply.\n" : "All seeders executed.\n";
    }

    /**
     * Retourne le nom de la classe du seeder à partir du fichier
     */
    private function getSeederClassName(string $filePath): ?string
    {
        $contents = file_get_contents($filePath);
        if (preg_match('/namespace\s+([^;]+);/i', $contents, $namespaceMatch) &&
            preg_match('/class\s+([a-zA-Z0-9_]+)/i', $contents, $classMatch)) {
            return trim($namespaceMatch[1]) . '\\' . trim($classMatch[1]);
        }

        return null;
    }
}
