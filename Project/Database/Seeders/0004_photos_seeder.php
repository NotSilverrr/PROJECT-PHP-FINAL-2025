<?php

namespace Database\Seeders;

use Core\Database;
use PDO;

class PhotoSeeder
{
    public function run(): void
    {
        echo "Seeding photos...\n";
        $pdo = Database::getConnection();
        
        $stmt = $pdo->prepare("
            INSERT INTO photos (file, group_id, user_id, created_at, updated_at)
            VALUES 
                ('photo1.jpg', 1, 1, NOW(), NOW()),
                ('photo2.jpg', 1, 2, NOW(), NOW()),
                ('photo3.jpg', 2, 3, NOW(), NOW()),
                ('photo4.jpg', 3, 1, NOW(), NOW())
        ");
        $stmt->execute();

        echo "Photos seeded.\n";
    }
}
