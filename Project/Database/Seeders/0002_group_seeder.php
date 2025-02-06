<?php

namespace Database\Seeders;

use Core\Database;

class GroupSeeder
{
    public function run(): void
    {
        echo "Seeding groups...\n";
        $pdo = Database::getConnection();
        
        $stmt = $pdo->prepare("
            INSERT INTO groups (name, profile_picture, owner, created_at, updated_at)
            VALUES 
                ('Developers', 'devs.jpg', 1, NOW(), NOW()),
                ('Designers', 'designers.jpg', 2, NOW(), NOW()),
                ('Gamers', 'gamers.jpg', 3, NOW(), NOW())
        ");
        $stmt->execute();

        echo "Groups seeded.\n";
    }
}
