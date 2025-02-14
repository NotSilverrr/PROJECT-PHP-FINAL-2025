<?php

namespace Database\Seeders;

use Core\Database;
use PDO;

class UserGroupSeeder
{
    public function run(): void
    {
        echo "Seeding user_group...\n";
        $pdo = Database::getConnection();
        
        $stmt = $pdo->prepare("
            INSERT INTO user_group (user_id, group_id, created_at)
            VALUES 
                (1, 1, NOW()),
                (2, 1, NOW()),
                (3, 2, NOW()),
                (1, 3, NOW()),
                (3, 1, NOW())
        ");
        $stmt->execute();

        echo "User-Group relations seeded.\n";
    }
}
